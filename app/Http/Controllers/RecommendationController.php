<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\UserIngredient;
use App\Models\UserPreference;
use App\Models\UserFoodHistory;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    /**
     * Display the recommendations page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Lấy các tham số filter
        $filters = [
            'quick_cook' => $request->boolean('quick_cook'), // ≤ 30 phút
            'sufficient_ingredients' => $request->boolean('sufficient_ingredients'), // Đủ nguyên liệu
            'healthy' => $request->boolean('healthy'),
            'low_calorie' => $request->boolean('low_calorie'),
            'vegetarian' => $request->boolean('vegetarian'),
            'spicy' => $request->boolean('spicy'),
        ];

        // Lấy danh sách món gợi ý
        $recommendations = $this->getRecommendations($user, $filters);

        return view('customer.recommendations.index', [
            'recommendations' => $recommendations,
            'filters' => $filters,
        ]);
    }

    /**
     * Get recommendations API endpoint.
     */
    public function getRecommendationsApi(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $filters = [
            'quick_cook' => $request->boolean('quick_cook'),
            'sufficient_ingredients' => $request->boolean('sufficient_ingredients'),
            'healthy' => $request->boolean('healthy'),
            'low_calorie' => $request->boolean('low_calorie'),
            'vegetarian' => $request->boolean('vegetarian'),
            'spicy' => $request->boolean('spicy'),
        ];

        $recommendations = $this->getRecommendations($user, $filters);

        return response()->json([
            'success' => true,
            'data' => $recommendations,
        ]);
    }

    /**
     * Core logic: Calculate recommendations for a user.
     */
    protected function getRecommendations($user, array $filters = [])
    {
        // 1. Thu thập dữ liệu đầu vào
        $userIngredients = UserIngredient::where('user_id', $user->id)
            ->whereHas('ingredient')
            ->pluck('ingredient_id')
            ->toArray();

        $preferences = $user->userPreference;

        // Lấy lịch sử gần đây (7 ngày qua)
        $recentHistory = UserFoodHistory::where('user_id', $user->id)
            ->where('action_at', '>=', now()->subDays(7))
            ->pluck('dish_id')
            ->toArray();

        // 2. Lấy tất cả món ăn active
        $query = Dish::where('status', 'active')
            ->with(['category', 'ingredients']);

        // 3. Loại bỏ món chứa nguyên liệu user dị ứng
        if ($preferences && $preferences->disliked_ingredients) {
            $dislikedIngredientIds = Ingredient::whereIn('name', $preferences->disliked_ingredients)
                ->pluck('id')
                ->toArray();
            
            if (!empty($dislikedIngredientIds)) {
                $query->whereDoesntHave('ingredients', function($q) use ($dislikedIngredientIds) {
                    $q->whereIn('ingredients.id', $dislikedIngredientIds);
                });
            }
        }

        // 4. Áp dụng filters
        if ($filters['quick_cook'] ?? false) {
            $query->whereRaw('(prep_time + cook_time) <= 30');
        }

        if ($filters['low_calorie'] ?? false) {
            $query->where('calories', '<=', 400);
        }

        $dishes = $query->get();

        // 5. Tính điểm gợi ý cho từng món
        $dishScores = [];
        
        foreach ($dishes as $dish) {
            $score = $this->calculateRecommendationScore(
                $dish,
                $userIngredients,
                $preferences,
                $recentHistory
            );

            // Chỉ thêm món có điểm > 0
            if ($score['total_score'] > 0) {
                // Áp dụng filters phức tạp hơn (cần kiểm tra từng món)
                if ($this->passesFilters($dish, $filters, $score)) {
                    $dishScores[] = [
                        'dish' => $dish,
                        'score' => $score,
                        'ingredient_match_ratio' => $score['ingredient_match_ratio'],
                        'missing_ingredients' => $score['missing_ingredients'],
                        'recommendation_reason' => $this->getRecommendationReason($score),
                    ];
                }
            }
        }

        // 6. Sắp xếp theo điểm giảm dần
        usort($dishScores, function($a, $b) {
            return $b['score']['total_score'] <=> $a['score']['total_score'];
        });

        // 7. Loại bỏ món user vừa nấu (trong 24h qua)
        $recentlyCooked = UserFoodHistory::where('user_id', $user->id)
            ->where('action', 'cooked')
            ->where('action_at', '>=', now()->subDay())
            ->pluck('dish_id')
            ->toArray();

        $dishScores = array_filter($dishScores, function($item) use ($recentlyCooked) {
            return !in_array($item['dish']->id, $recentlyCooked);
        });

        // 8. Lấy top 10
        return array_slice($dishScores, 0, 10);
    }

    /**
     * Calculate recommendation score for a dish.
     */
    protected function calculateRecommendationScore(
        Dish $dish,
        array $userIngredientIds,
        ?UserPreference $preferences,
        array $recentHistory
    ): array {
        // 1. Tính mức độ khớp nguyên liệu (40%)
        $dishIngredientIds = $dish->ingredients->pluck('id')->toArray();
        $matchedIngredients = array_intersect($dishIngredientIds, $userIngredientIds);
        $totalIngredients = count($dishIngredientIds);
        $matchedCount = count($matchedIngredients);
        
        $ingredientMatchRatio = $totalIngredients > 0 
            ? ($matchedCount / $totalIngredients) 
            : 0;

        $missingIngredientIds = array_diff($dishIngredientIds, $userIngredientIds);
        $missingIngredients = Ingredient::whereIn('id', $missingIngredientIds)
            ->get(['id', 'name', 'type']);

        // 2. Tính điểm phù hợp sở thích (30%)
        $preferenceMatch = 0;
        if ($preferences) {
            // Match category
            if ($preferences->favorite_categories && $dish->category) {
                if (in_array($dish->category->name, $preferences->favorite_categories)) {
                    $preferenceMatch += 0.15;
                }
            }

            // Match diet type (simplified - có thể mở rộng)
            // Match spicy level (simplified - có thể mở rộng dựa trên tags/description)
            $spicyMatch = $this->calculateSpicyMatch($dish, $preferences->spicy_level);
            $preferenceMatch += $spicyMatch * 0.1;

            // Health goal (simplified)
            if ($preferences->health_goal && $dish->calories) {
                if ($preferences->health_goal === 'lose_weight' && $dish->calories <= 400) {
                    $preferenceMatch += 0.05;
                } elseif ($preferences->health_goal === 'gain_weight' && $dish->calories >= 600) {
                    $preferenceMatch += 0.05;
                }
            }
        }

        // Normalize preference match to 0-1
        $preferenceMatch = min(1, $preferenceMatch);

        // 3. Tính điểm lịch sử (20%)
        $historyWeight = 0;
        if (in_array($dish->id, $recentHistory)) {
            // Nếu user đã xem/nấu món này gần đây, giảm điểm (tránh lặp lại)
            $historyWeight = -0.1; // Penalty cho món đã xem
        } else {
            // Nếu chưa xem, thêm điểm
            $historyWeight = 0.1;
        }

        // 4. Tính điểm đánh giá (10%)
        $ratingScore = 0;
        $averageRating = $dish->average_rating;
        if ($averageRating > 0) {
            // Normalize rating (0-5) to (0-1)
            $ratingScore = $averageRating / 5;
        }

        // 5. Tính điểm tổng
        $totalScore = 
            (0.4 * $ingredientMatchRatio) +
            (0.3 * $preferenceMatch) +
            (0.2 * (0.5 + $historyWeight)) + // Normalize history weight
            (0.1 * $ratingScore);

        return [
            'ingredient_match_ratio' => $ingredientMatchRatio,
            'preference_match' => $preferenceMatch,
            'history_weight' => $historyWeight,
            'rating_score' => $ratingScore,
            'total_score' => $totalScore,
            'missing_ingredients' => $missingIngredients,
        ];
    }

    /**
     * Calculate spicy match score.
     */
    protected function calculateSpicyMatch(Dish $dish, int $userSpicyLevel): float
    {
        // Simplified: Check if dish name/description contains spicy keywords
        $spicyKeywords = ['cay', 'spicy', 'ớt', 'chilli'];
        $dishText = strtolower($dish->name . ' ' . ($dish->description ?? ''));
        
        $isSpicy = false;
        foreach ($spicyKeywords as $keyword) {
            if (strpos($dishText, $keyword) !== false) {
                $isSpicy = true;
                break;
            }
        }

        // Match user preference
        if ($userSpicyLevel >= 2 && $isSpicy) {
            return 1.0; // User likes spicy, dish is spicy
        } elseif ($userSpicyLevel < 2 && !$isSpicy) {
            return 1.0; // User doesn't like spicy, dish is not spicy
        }

        return 0.5; // Partial match
    }

    /**
     * Check if dish passes filters.
     */
    protected function passesFilters(Dish $dish, array $filters, array $score): bool
    {
        if ($filters['sufficient_ingredients'] ?? false) {
            if ($score['ingredient_match_ratio'] < 1.0) {
                return false; // Không đủ nguyên liệu
            }
        }

        // Healthy filter (simplified - check calories)
        if ($filters['healthy'] ?? false) {
            if (!$dish->calories || $dish->calories > 500) {
                return false;
            }
        }

        // Vegetarian filter (simplified - check category or description)
        if ($filters['vegetarian'] ?? false) {
            $dishText = strtolower($dish->name . ' ' . ($dish->description ?? ''));
            $meatKeywords = ['thịt', 'cá', 'tôm', 'gà', 'bò', 'heo', 'meat', 'fish', 'chicken', 'beef', 'pork'];
            foreach ($meatKeywords as $keyword) {
                if (strpos($dishText, $keyword) !== false) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get recommendation reason text.
     */
    protected function getRecommendationReason(array $score): string
    {
        $reasons = [];

        if ($score['ingredient_match_ratio'] >= 0.9) {
            $reasons[] = 'Bạn đã có sẵn ' . round($score['ingredient_match_ratio'] * 100) . '% nguyên liệu';
        } elseif ($score['ingredient_match_ratio'] >= 0.7) {
            $reasons[] = 'Phù hợp với nguyên liệu bạn đang có';
        }

        if ($score['preference_match'] > 0.5) {
            $reasons[] = 'Phù hợp sở thích của bạn';
        }

        if ($score['rating_score'] > 0.8) {
            $reasons[] = 'Được đánh giá cao';
        }

        return !empty($reasons) ? implode(' • ', $reasons) : 'Gợi ý dành cho bạn';
    }

    /**
     * Log dish view action.
     */
    public function logView(Request $request, Dish $dish): JsonResponse
    {
        $user = $request->user();
        
        // Chỉ log một lần trong 24h
        $existing = UserFoodHistory::where('user_id', $user->id)
            ->where('dish_id', $dish->id)
            ->where('action', 'viewed')
            ->where('action_at', '>=', now()->subDay())
            ->first();

        if (!$existing) {
            UserFoodHistory::create([
                'user_id' => $user->id,
                'dish_id' => $dish->id,
                'action' => 'viewed',
                'action_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Log dish cook action.
     */
    public function logCook(Request $request, Dish $dish): JsonResponse
    {
        $user = $request->user();
        
        UserFoodHistory::create([
            'user_id' => $user->id,
            'dish_id' => $dish->id,
            'action' => 'cooked',
            'action_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
