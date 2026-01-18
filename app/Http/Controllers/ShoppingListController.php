<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\UserIngredient;
use App\Models\UserPreference;
use App\Models\UserFoodHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShoppingListController extends Controller
{
    /**
     * Display the shopping list page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Lấy danh sách món gợi ý (từ RecommendationController logic)
        $recommendations = $this->getRecommendedDishes($user);
        
        // Lấy danh sách món từ recommendations
        // $recommendations là array of arrays với key 'dish' chứa Dish object
        $recommendedDishes = collect($recommendations)->map(function($item) {
            return $item['dish'];
        })->values();
        $recommendedDishIds = $recommendedDishes->pluck('id')->toArray();
        
        // Lấy nguyên liệu thiếu cho các món được gợi ý (mặc định)
        $selectedDishIds = $request->get('dishes', $recommendedDishIds);
        if (is_string($selectedDishIds)) {
            $selectedDishIds = explode(',', $selectedDishIds);
        }
        
        $missingIngredients = $this->calculateMissingIngredients($user, $selectedDishIds);
        
        // Lấy thông tin món để hiển thị trong view (tạo map để dễ truy cập)
        $dishesMap = $recommendedDishes->keyBy('id');
        
        return view('customer.shopping-list.index', [
            'recommendedDishes' => $recommendedDishes,
            'selectedDishIds' => $selectedDishIds,
            'missingIngredients' => $missingIngredients,
            'dishesMap' => $dishesMap,
        ]);
    }
    
    /**
     * Get recommended dishes for user (reuse RecommendationController logic).
     */
    protected function getRecommendedDishes($user): array
    {
        $recommendationController = new RecommendationController();
        // Use reflection to call protected method
        $reflection = new \ReflectionClass($recommendationController);
        $method = $reflection->getMethod('getRecommendations');
        $method->setAccessible(true);
        return $method->invoke($recommendationController, $user, []);
    }

    /**
     * API: Get missing ingredients for selected dishes.
     */
    public function getMissingIngredients(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng chưa đăng nhập.',
                ], 401);
            }
            
            $selectedDishIds = $request->get('dish_ids', []);
            if (is_string($selectedDishIds)) {
                $selectedDishIds = array_filter(array_map('intval', explode(',', $selectedDishIds)));
            } else {
                $selectedDishIds = array_filter(array_map('intval', (array)$selectedDishIds));
            }
            
            if (empty($selectedDishIds)) {
                // Nếu không có dish_ids, lấy từ recommendations
                $recommendations = $this->getRecommendedDishes($user);
                $selectedDishIds = collect($recommendations)->map(function($item) {
                    return $item['dish']->id;
                })->toArray();
            }
            
            $missingIngredients = $this->calculateMissingIngredients($user, $selectedDishIds);
            
            return response()->json([
                'success' => true,
                'data' => $missingIngredients,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getMissingIngredients: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải danh sách nguyên liệu.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * API: Mark ingredient as purchased (add to user_ingredients).
     */
    public function markAsPurchased(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'quantity' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:255'],
        ]);
        
        // Kiểm tra nguyên liệu đã tồn tại chưa
        $existing = UserIngredient::where('user_id', $user->id)
            ->where('ingredient_id', $validated['ingredient_id'])
            ->first();
        
        if ($existing) {
            // Cập nhật quantity và unit nếu có
            if (isset($validated['quantity'])) {
                $existing->update([
                    'quantity' => $validated['quantity'],
                    'unit' => $validated['unit'] ?? $existing->unit,
                    'added_at' => now(),
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Nguyên liệu đã được cập nhật!',
                'data' => $existing->load('ingredient'),
            ]);
        }
        
        // Tạo mới
        $userIngredient = UserIngredient::create([
            'user_id' => $user->id,
            'ingredient_id' => $validated['ingredient_id'],
            'quantity' => $validated['quantity'] ?? null,
            'unit' => $validated['unit'] ?? null,
            'added_at' => now(),
        ]);
        
        $userIngredient->load('ingredient');
        
        return response()->json([
            'success' => true,
            'message' => 'Đã thêm nguyên liệu vào tủ lạnh của bạn!',
            'data' => $userIngredient,
        ], 201);
    }

    /**
     * Core logic: Calculate missing ingredients for selected dishes.
     */
    protected function calculateMissingIngredients($user, array $dishIds): array
    {
        if (empty($dishIds)) {
            return [];
        }
        
        // 1. Lấy nguyên liệu user đang có
        $userIngredientIds = UserIngredient::where('user_id', $user->id)
            ->whereHas('ingredient')
            ->pluck('ingredient_id')
            ->toArray();
        
        // 2. Lấy preferences để loại bỏ nguyên liệu dị ứng/không thích
        $preferences = $user->userPreference;
        $dislikedIngredientIds = [];
        if ($preferences && $preferences->disliked_ingredients) {
            $dislikedIngredientIds = Ingredient::whereIn('name', $preferences->disliked_ingredients)
                ->pluck('id')
                ->toArray();
        }
        
        // 3. Lấy tất cả nguyên liệu cần thiết cho các món được chọn
        $dishes = Dish::whereIn('id', $dishIds)
            ->where('status', 'active')
            ->with(['ingredients' => function($query) {
                $query->select('ingredients.id', 'ingredients.name', 'ingredients.type', 'ingredients.unit')
                      ->withPivot('quantity', 'unit', 'is_required');
            }])
            ->get();
        
        // 4. Tính nguyên liệu thiếu cho từng món và gộp lại
        $missingIngredientsMap = [];
        
        foreach ($dishes as $dish) {
            $dishIngredientIds = $dish->ingredients->pluck('id')->toArray();
            
            foreach ($dish->ingredients as $ingredient) {
                $ingredientId = $ingredient->id;
                
                // Bỏ qua nếu user đã có nguyên liệu này
                if (in_array($ingredientId, $userIngredientIds)) {
                    continue;
                }
                
                // Bỏ qua nếu user dị ứng/không thích
                if (in_array($ingredientId, $dislikedIngredientIds)) {
                    continue;
                }
                
                // Gộp nguyên liệu theo nhiều món
                if (!isset($missingIngredientsMap[$ingredientId])) {
                    $missingIngredientsMap[$ingredientId] = [
                        'ingredient_id' => $ingredientId,
                        'ingredient_name' => $ingredient->name,
                        'ingredient_type' => $ingredient->type,
                        'ingredient_unit' => $ingredient->unit,
                        'required_for_dishes' => [],
                        'required_count' => 0,
                        'suggested_quantity' => null,
                        'is_required' => false,
                    ];
                }
                
                // Thêm dish_id vào danh sách món cần nguyên liệu này
                if (!in_array($dish->id, $missingIngredientsMap[$ingredientId]['required_for_dishes'])) {
                    $missingIngredientsMap[$ingredientId]['required_for_dishes'][] = $dish->id;
                    $missingIngredientsMap[$ingredientId]['required_count']++;
                }
                
                // Lấy thông tin quantity từ pivot nếu có
                $pivotIngredient = $dish->ingredients->where('id', $ingredientId)->first();
                if ($pivotIngredient && $pivotIngredient->pivot) {
                    if ($pivotIngredient->pivot->quantity) {
                        $missingIngredientsMap[$ingredientId]['suggested_quantity'] = $pivotIngredient->pivot->quantity;
                    }
                    if ($pivotIngredient->pivot->is_required) {
                        $missingIngredientsMap[$ingredientId]['is_required'] = true;
                    }
                }
            }
        }
        
        // 5. Tính điểm ưu tiên và sắp xếp
        $missingIngredients = array_values($missingIngredientsMap);
        
        // Lấy thông tin lịch sử nấu ăn để tính điểm ưu tiên
        $recentHistory = UserFoodHistory::where('user_id', $user->id)
            ->where('action_at', '>=', now()->subDays(7))
            ->pluck('dish_id')
            ->toArray();
        
        foreach ($missingIngredients as &$item) {
            // Tính điểm ưu tiên
            $priorityScore = $item['required_count'] * 10; // Mỗi món cần = 10 điểm
            
            // Thêm điểm nếu các món cần nguyên liệu này đã được user xem/nấu gần đây
            $dishHistoryCount = count(array_intersect($item['required_for_dishes'], $recentHistory));
            $priorityScore += $dishHistoryCount * 5;
            
            // Thêm điểm nếu là nguyên liệu bắt buộc
            if ($item['is_required']) {
                $priorityScore += 5;
            }
            
            $item['priority_score'] = $priorityScore;
        }
        
        // Sắp xếp theo điểm ưu tiên giảm dần
        usort($missingIngredients, function($a, $b) {
            return $b['priority_score'] <=> $a['priority_score'];
        });
        
        return $missingIngredients;
    }
}

