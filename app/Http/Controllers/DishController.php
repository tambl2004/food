<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Product;
use App\Models\UserFoodHistory;
use App\Models\UserIngredient;
use App\Models\UserPreference;
use App\Models\FavoriteDish;
use App\Models\Review;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Display the specified dish.
     */
    public function show(Request $request, Dish $dish)
    {
        // Kiểm tra dish có active không
        if ($dish->status !== 'active') {
            abort(404);
        }

        // Load relationships
        $dish->load(['category', 'ingredients', 'visibleReviews.user']);

        $user = $request->user();
        
        // So sánh nguyên liệu user có/thiếu (chỉ khi user đăng nhập)
        $ingredientStatus = [];
        $ingredientMatchRatio = 0;
        $missingIngredients = [];
        $availableIngredients = [];
        
        if ($user) {
            // Lấy nguyên liệu user có
            $userIngredientIds = UserIngredient::where('user_id', $user->id)
                ->whereHas('ingredient')
                ->pluck('ingredient_id')
                ->toArray();

            // So sánh với nguyên liệu của món
            $dishIngredientIds = $dish->ingredients->pluck('id')->toArray();
            
            foreach ($dish->ingredients as $ingredient) {
                $status = in_array($ingredient->id, $userIngredientIds) ? 'AVAILABLE' : 'MISSING';
                $ingredientStatus[$ingredient->id] = $status;
                
                if ($status === 'AVAILABLE') {
                    $availableIngredients[] = $ingredient->id;
                } else {
                    $missingIngredients[] = [
                        'id' => $ingredient->id,
                        'name' => $ingredient->name,
                        'quantity' => $ingredient->pivot->quantity ?? null,
                        'unit' => $ingredient->pivot->unit ?? $ingredient->unit ?? null,
                    ];
                }
            }

            // Tính tỉ lệ nguyên liệu có sẵn
            $totalIngredients = count($dishIngredientIds);
            if ($totalIngredients > 0) {
                $ingredientMatchRatio = count($availableIngredients) / $totalIngredients;
            }

            // Log view action (chỉ log một lần trong 24h)
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
        }

        // Tính AI recommendation reason (nếu đi từ gợi ý AI)
        $aiReason = null;
        $fromRecommendations = $request->has('from') && $request->get('from') === 'recommendations';
        
        if ($user && $fromRecommendations) {
            $preferences = $user->userPreference;
            $reasons = [];

            // Lý do 1: Phù hợp nguyên liệu
            if ($ingredientMatchRatio >= 0.7) {
                $reasons[] = 'Bạn đã có sẵn ' . round($ingredientMatchRatio * 100) . '% nguyên liệu';
            }

            // Lý do 2: Phù hợp sở thích
            if ($preferences && $preferences->favorite_categories && $dish->category) {
                if (in_array($dish->category->name, $preferences->favorite_categories)) {
                    $reasons[] = 'Phù hợp sở thích của bạn';
                }
            }

            // Lý do 3: Đã từng nấu món tương tự
            $similarDishesHistory = UserFoodHistory::where('user_id', $user->id)
                ->where('action', 'cooked')
                ->whereHas('dish', function($query) use ($dish) {
                    $query->where('category_id', $dish->category_id)
                          ->where('id', '!=', $dish->id);
                })
                ->exists();
            
            if ($similarDishesHistory) {
                $reasons[] = 'Bạn từng nấu món tương tự';
            }

            if (!empty($reasons)) {
                $aiReason = implode(' • ', $reasons);
            }
        }

        // Lấy các dish liên quan (cùng category hoặc cùng nguyên liệu chính)
        $relatedDishes = $this->getRelatedDishes($dish, 4);

        // Kiểm tra user đã favorite chưa (nếu đăng nhập)
        $isFavorite = false;
        if ($user) {
            // FavoriteDish chỉ có product_id, nên check qua product tương ứng
            $product = Product::where('name', $dish->name)->first();
            if ($product) {
                $isFavorite = FavoriteDish::where('user_id', $user->id)
                    ->where('product_id', $product->id)
                    ->exists();
            }
        }

        // Kiểm tra user đã đánh giá chưa (nếu đăng nhập)
        $userReview = null;
        if ($user) {
            $userReview = Review::where('user_id', $user->id)
                ->where('dish_id', $dish->id)
                ->first();
        }

        // Kiểm tra user đã nấu món này chưa (để cho phép đánh giá)
        $hasCooked = false;
        if ($user) {
            $hasCooked = UserFoodHistory::where('user_id', $user->id)
                ->where('dish_id', $dish->id)
                ->where('action', 'cooked')
                ->exists();
        }

        return view('customer.dishes.show', [
            'dish' => $dish,
            'relatedDishes' => $relatedDishes,
            'ingredientStatus' => $ingredientStatus,
            'ingredientMatchRatio' => $ingredientMatchRatio,
            'missingIngredients' => $missingIngredients,
            'aiReason' => $aiReason,
            'fromRecommendations' => $fromRecommendations,
            'isFavorite' => $isFavorite,
            'userReview' => $userReview,
            'hasCooked' => $hasCooked,
        ]);
    }

    /**
     * Get related dishes (same category or same main ingredients).
     */
    protected function getRelatedDishes(Dish $dish, int $limit = 4)
    {
        // Lấy món cùng category
        $relatedByCategory = Dish::where('status', 'active')
            ->where('category_id', $dish->category_id)
            ->where('id', '!=', $dish->id)
            ->limit($limit)
            ->get();

        // Nếu chưa đủ, lấy thêm món có nguyên liệu chung (lấy 2-3 nguyên liệu đầu)
        if ($relatedByCategory->count() < $limit) {
            $mainIngredientIds = $dish->ingredients->take(3)->pluck('id')->toArray();
            
            if (!empty($mainIngredientIds)) {
                $relatedByIngredients = Dish::where('status', 'active')
                    ->where('id', '!=', $dish->id)
                    ->whereHas('ingredients', function($query) use ($mainIngredientIds) {
                        $query->whereIn('ingredients.id', $mainIngredientIds);
                    }) // Có ít nhất 1 nguyên liệu chung
                    ->whereNotIn('id', $relatedByCategory->pluck('id')->toArray()) // Loại bỏ món đã có
                    ->limit($limit - $relatedByCategory->count())
                    ->get();

                $relatedDishes = $relatedByCategory->merge($relatedByIngredients);
            } else {
                $relatedDishes = $relatedByCategory;
            }
        } else {
            $relatedDishes = $relatedByCategory;
        }

        return $relatedDishes->take($limit);
    }

    /**
     * Log cook action - User đã nấu món này.
     */
    public function cook(Request $request, Dish $dish)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng chức năng này.',
            ], 401);
        }

        // Ghi lịch sử COOKED
        UserFoodHistory::create([
            'user_id' => $user->id,
            'dish_id' => $dish->id,
            'action' => 'cooked',
            'action_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã ghi nhận bạn đã nấu món này!',
            ]);
        }

        return redirect()->back()->with('success', 'Đã ghi nhận bạn đã nấu món này!');
    }

    /**
     * Toggle favorite - Thêm/xóa món yêu thích.
     */
    public function toggleFavorite(Request $request, Dish $dish)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng chức năng này.',
            ], 401);
        }

        // FavoriteDish chỉ có product_id, nên cần tìm product tương ứng
        $product = Product::where('name', $dish->name)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm tương ứng.',
            ], 404);
        }

        $favorite = FavoriteDish::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            // Xóa favorite
            $favorite->delete();
            $message = 'Đã xóa khỏi món yêu thích!';
            $isFavorite = false;
        } else {
            // Thêm favorite
            FavoriteDish::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            
            // Ghi lịch sử SAVED
            UserFoodHistory::create([
                'user_id' => $user->id,
                'dish_id' => $dish->id,
                'action' => 'saved',
                'action_at' => now(),
            ]);
            
            $message = 'Đã thêm vào món yêu thích!';
            $isFavorite = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorite' => $isFavorite,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Thêm nguyên liệu còn thiếu vào shopping list.
     */
    public function addMissingIngredients(Request $request, Dish $dish)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để sử dụng chức năng này.',
            ], 401);
        }

        // Lấy nguyên liệu user có
        $userIngredientIds = UserIngredient::where('user_id', $user->id)
            ->whereHas('ingredient')
            ->pluck('ingredient_id')
            ->toArray();

        // Lấy nguyên liệu thiếu của món
        $dishIngredientIds = $dish->ingredients->pluck('id')->toArray();
        $missingIngredientIds = array_diff($dishIngredientIds, $userIngredientIds);

        if (empty($missingIngredientIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã có đủ nguyên liệu để nấu món này!',
            ], 400);
        }

        // Điều hướng đến shopping list với dish_id được chọn
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã chuyển đến trang danh sách mua sắm!',
                'redirect_url' => route('shopping-list.index', ['dishes' => $dish->id]),
            ]);
        }

        return redirect()->route('shopping-list.index', ['dishes' => $dish->id])
            ->with('success', 'Đã thêm nguyên liệu còn thiếu vào danh sách mua sắm!');
    }
}
