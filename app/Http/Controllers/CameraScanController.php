<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\IngredientNutrition;
use App\Models\Dish;
use App\Models\UserIngredient;
use App\Models\ScanHistory;
use App\Services\CameraVisionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CameraScanController extends Controller
{
    protected $visionService;

    public function __construct(CameraVisionService $visionService)
    {
        $this->visionService = $visionService;
    }

    /**
     * Hiển thị trang camera scan
     */
    public function index(): View
    {
        return view('customer.camera.scan');
    }

    /**
     * API: Nhận diện nguyên liệu từ ảnh
     * POST /api/ai/vision/ingredients
     */
    public function detectIngredients(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:10240'], // Max 10MB
        ]);

        try {
            $user = $request->user();
            
            // Lưu ảnh tạm thời
            $imagePath = $request->file('image')->store('temp/scans', 'public');
            $fullPath = storage_path('app/public/' . $imagePath);

            // Nhận diện nguyên liệu
            $detections = $this->visionService->detectIngredients($fullPath);

            // Lưu vào scan_history (optional)
            if ($request->boolean('save_history', false)) {
                ScanHistory::create([
                    'user_id' => $user->id,
                    'image_path' => $imagePath,
                    'detected_ingredients' => $detections,
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'detections' => $detections,
                    'image_url' => Storage::url($imagePath),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi nhận diện nguyên liệu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Phân tích dinh dưỡng từ danh sách nguyên liệu
     * POST /api/ai/nutrition-analysis
     */
    public function analyzeNutrition(Request $request): JsonResponse
    {
        $request->validate([
            'ingredient_ids' => ['required', 'array'],
            'ingredient_ids.*' => ['required', 'exists:ingredients,id'],
        ]);

        try {
            $ingredientIds = $request->ingredient_ids;
            
            // Lấy thông tin dinh dưỡng của các nguyên liệu
            $nutritionData = IngredientNutrition::whereIn('ingredient_id', $ingredientIds)
                ->with('ingredient')
                ->get();

            // Tính tổng dinh dưỡng (giả sử mỗi nguyên liệu 100g)
            $totalCalories = 0;
            $totalProtein = 0;
            $totalFat = 0;
            $totalCarbs = 0;
            $totalFiber = 0;
            $vitamins = [];

            foreach ($nutritionData as $nutrition) {
                $totalCalories += $nutrition->calories ?? 0;
                $totalProtein += $nutrition->protein ?? 0;
                $totalFat += $nutrition->fat ?? 0;
                $totalCarbs += $nutrition->carbs ?? 0;
                $totalFiber += $nutrition->fiber ?? 0;
                
                if ($nutrition->vitamins) {
                    $vitamins[] = $nutrition->vitamins;
                }
            }

            // Tạo nhận xét AI
            $comment = $this->generateNutritionComment($totalCalories, $totalProtein, $totalFat, $totalCarbs);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_calories' => round($totalCalories, 2),
                    'protein' => round($totalProtein, 2),
                    'fat' => round($totalFat, 2),
                    'carbs' => round($totalCarbs, 2),
                    'fiber' => round($totalFiber, 2),
                    'vitamins' => array_unique($vitamins),
                    'nutrition_comment' => $comment,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi phân tích dinh dưỡng: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Lưu nguyên liệu từ camera vào user_ingredients
     * POST /api/user/ingredients/from-camera
     */
    public function saveIngredients(Request $request): JsonResponse
    {
        $request->validate([
            'ingredient_ids' => ['required', 'array'],
            'ingredient_ids.*' => ['required', 'exists:ingredients,id'],
        ]);

        try {
            $user = $request->user();
            $ingredientIds = $request->ingredient_ids;
            $saved = [];
            $skipped = [];

            DB::beginTransaction();

            foreach ($ingredientIds as $ingredientId) {
                // Kiểm tra đã tồn tại chưa
                $existing = UserIngredient::where('user_id', $user->id)
                    ->where('ingredient_id', $ingredientId)
                    ->first();

                if ($existing) {
                    // Cập nhật added_at
                    $existing->update(['added_at' => now()]);
                    $skipped[] = $ingredientId;
                } else {
                    // Tạo mới
                    $ingredient = Ingredient::find($ingredientId);
                    $userIngredient = UserIngredient::create([
                        'user_id' => $user->id,
                        'ingredient_id' => $ingredientId,
                        'quantity' => null,
                        'unit' => $ingredient->unit ?? 'g',
                        'added_at' => now(),
                    ]);
                    $saved[] = $userIngredient->load('ingredient');
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu ' . count($saved) . ' nguyên liệu vào bếp của bạn!',
                'data' => [
                    'saved' => $saved,
                    'skipped_count' => count($skipped),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lưu nguyên liệu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * API: Gợi ý món ăn từ nguyên liệu đã nhận diện
     * POST /api/ai/suggest-dishes
     */
    public function suggestDishes(Request $request): JsonResponse
    {
        $request->validate([
            'ingredient_ids' => ['required', 'array'],
            'ingredient_ids.*' => ['required', 'exists:ingredients,id'],
        ]);

        try {
            $ingredientIds = $request->ingredient_ids;
            $user = $request->user();

            // Lấy danh sách nguyên liệu user đã có
            $userIngredientIds = UserIngredient::where('user_id', $user->id)
                ->pluck('ingredient_id')
                ->toArray();

            // Tìm các món ăn có chứa nguyên liệu đã nhận diện
            $dishes = Dish::where('status', 'active')
                ->whereHas('ingredients', function($query) use ($ingredientIds) {
                    $query->whereIn('ingredients.id', $ingredientIds);
                })
                ->with(['ingredients' => function($query) {
                    $query->select('ingredients.id', 'ingredients.name');
                }])
                ->with('category')
                ->get();

            // Tính toán match rate và missing ingredients
            $suggestions = $dishes->map(function($dish) use ($ingredientIds, $userIngredientIds) {
                $dishIngredientIds = $dish->ingredients->pluck('id')->toArray();
                
                // Nguyên liệu có trong ảnh
                $matchedFromScan = array_intersect($dishIngredientIds, $ingredientIds);
                
                // Nguyên liệu user đã có (bao gồm cả từ scan)
                $allUserIngredients = array_unique(array_merge($userIngredientIds, $ingredientIds));
                $matchedFromUser = array_intersect($dishIngredientIds, $allUserIngredients);
                
                // Nguyên liệu còn thiếu
                $missingIngredientIds = array_diff($dishIngredientIds, $allUserIngredients);
                $missingIngredients = Ingredient::whereIn('id', $missingIngredientIds)
                    ->get(['id', 'name', 'type']);

                $totalIngredients = count($dishIngredientIds);
                $matchRate = $totalIngredients > 0 ? (count($matchedFromUser) / $totalIngredients) * 100 : 0;

                // Tạo lý do gợi ý
                $reason = $this->generateSuggestionReason($matchedFromScan, $dish->ingredients, $missingIngredients);

                return [
                    'dish_id' => $dish->id,
                    'dish_name' => $dish->name,
                    'dish_slug' => $dish->slug,
                    'dish_image' => $dish->image,
                    'match_rate' => round($matchRate, 1),
                    'matched_count' => count($matchedFromUser),
                    'total_count' => $totalIngredients,
                    'missing_ingredients' => $missingIngredients,
                    'reason' => $reason,
                ];
            })->sortByDesc('match_rate')->take(5)->values();

            return response()->json([
                'success' => true,
                'data' => $suggestions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gợi ý món ăn: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tạo nhận xét dinh dưỡng
     */
    private function generateNutritionComment($calories, $protein, $fat, $carbs): string
    {
        $comments = [];

        if ($protein > 20) {
            $comments[] = 'Giàu protein';
        } elseif ($protein < 5) {
            $comments[] = 'Ít protein';
        }

        if ($fat < 5) {
            $comments[] = 'Ít chất béo';
        } elseif ($fat > 30) {
            $comments[] = 'Nhiều chất béo';
        }

        if ($calories < 200) {
            $comments[] = 'Phù hợp ăn kiêng';
        } elseif ($calories > 500) {
            $comments[] = 'Nhiều năng lượng';
        }

        if (empty($comments)) {
            $comments[] = 'Cân bằng dinh dưỡng';
        }

        return implode(', ', $comments);
    }

    /**
     * Tạo lý do gợi ý món ăn
     */
    private function generateSuggestionReason(array $matchedFromScan, $dishIngredients, $missingIngredients): string
    {
        $matchedNames = [];
        foreach ($matchedFromScan as $ingredientId) {
            $ingredient = $dishIngredients->firstWhere('id', $ingredientId);
            if ($ingredient) {
                $matchedNames[] = $ingredient->name;
            }
        }

        if (count($matchedNames) > 0) {
            $ingredientList = implode(', ', array_slice($matchedNames, 0, 3));
            if (count($matchedNames) > 3) {
                $ingredientList .= ' và ' . (count($matchedNames) - 3) . ' nguyên liệu khác';
            }
            return "Bạn đã có sẵn: {$ingredientList}";
        }

        return "Phù hợp với nguyên liệu bạn có";
    }
}
