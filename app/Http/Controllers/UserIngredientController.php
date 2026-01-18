<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\UserIngredient;
use App\Models\Dish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class UserIngredientController extends Controller
{
    /**
     * Display the user's ingredients page.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Lấy danh sách nguyên liệu của user với thông tin ingredient
        // Chỉ lấy những nguyên liệu còn tồn tại (không bị xoá)
        $query = UserIngredient::where('user_id', $user->id)
            ->whereHas('ingredient')
            ->with('ingredient')
            ->orderBy('updated_at', 'desc');

        // Tìm kiếm theo tên nguyên liệu
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('ingredient', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Lọc theo loại nguyên liệu
        if ($request->has('type') && $request->type != '') {
            $query->whereHas('ingredient', function($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        $userIngredients = $query->get();

        // Lấy danh sách các loại nguyên liệu để filter
        $ingredientTypes = Ingredient::distinct()
            ->whereNotNull('type')
            ->where('status', 'active')
            ->pluck('type')
            ->sort()
            ->values();

        // Tính số món ăn có thể nấu (100% đủ nguyên liệu)
        $cookableDishesCount = 0;
        if ($userIngredients->count() > 0) {
            $userIngredientIds = $userIngredients->pluck('ingredient_id')->toArray();
            
            $dishes = Dish::where('status', 'active')
                ->whereHas('ingredients', function($query) use ($userIngredientIds) {
                    $query->whereIn('ingredients.id', $userIngredientIds);
                })
                ->with('ingredients')
                ->get();

            foreach ($dishes as $dish) {
                $dishIngredientIds = $dish->ingredients->pluck('id')->toArray();
                $matchedIngredients = array_intersect($dishIngredientIds, $userIngredientIds);
                $totalIngredients = count($dishIngredientIds);
                $matchedCount = count($matchedIngredients);
                
                // Nếu đủ 100% nguyên liệu
                if ($matchedCount === $totalIngredients && $totalIngredients > 0) {
                    $cookableDishesCount++;
                }
            }
        }

        return view('customer.ingredients.index', [
            'userIngredients' => $userIngredients,
            'ingredientTypes' => $ingredientTypes,
            'cookableDishesCount' => $cookableDishesCount,
        ]);
    }

    /**
     * Store a newly created user ingredient.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
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
            // Nếu đã tồn tại, cập nhật quantity và unit và reset added_at
            $existing->update([
                'quantity' => $validated['quantity'] ?? $existing->quantity,
                'unit' => $validated['unit'] ?? $existing->unit,
                'added_at' => now(), // Reset ngày thêm khi cập nhật
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật nguyên liệu thành công!',
                    'data' => $existing->load('ingredient'),
                ]);
            }

            return redirect()->route('user.ingredients.index')
                ->with('success', 'Cập nhật nguyên liệu thành công!');
        }

        // Nếu chưa tồn tại, tạo mới
        $validated['user_id'] = $user->id;
        $validated['added_at'] = now(); // Thêm ngày thêm vào tủ lạnh
        $userIngredient = UserIngredient::create($validated);
        $userIngredient->load('ingredient');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm nguyên liệu thành công!',
                'data' => $userIngredient,
            ], 201);
        }

        return redirect()->route('user.ingredients.index')
            ->with('success', 'Thêm nguyên liệu thành công!');
    }

    /**
     * Update the specified user ingredient.
     */
    public function update(Request $request, UserIngredient $userIngredient): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        // Kiểm tra user có quyền chỉnh sửa
        if ($userIngredient->user_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền chỉnh sửa nguyên liệu này!',
                ], 403);
            }
            return redirect()->route('user.ingredients.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa nguyên liệu này!');
        }

        $validated = $request->validate([
            'quantity' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:255'],
        ]);

        $userIngredient->update($validated);
        $userIngredient->load('ingredient');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật nguyên liệu thành công!',
                'data' => $userIngredient,
            ]);
        }

        return redirect()->route('user.ingredients.index')
            ->with('success', 'Cập nhật nguyên liệu thành công!');
    }

    /**
     * Remove the specified user ingredient.
     */
    public function destroy(Request $request, UserIngredient $userIngredient): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        // Kiểm tra user có quyền xoá
        if ($userIngredient->user_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xoá nguyên liệu này!',
                ], 403);
            }
            return redirect()->route('user.ingredients.index')
                ->with('error', 'Bạn không có quyền xoá nguyên liệu này!');
        }

        $userIngredient->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Xoá nguyên liệu thành công!',
            ]);
        }

        return redirect()->route('user.ingredients.index')
            ->with('success', 'Xoá nguyên liệu thành công!');
    }

    /**
     * Get list of available ingredients for autocomplete.
     */
    public function getIngredients(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $type = $request->get('type', '');
        
        $ingredients = Ingredient::where('status', 'active')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($type, function($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'type', 'unit']);

        return response()->json([
            'success' => true,
            'data' => $ingredients,
        ]);
    }

    /**
     * Tìm kiếm món ăn dựa trên nguyên liệu của user
     */
    public function findDishes(Request $request): View|JsonResponse
    {
        $user = $request->user();
        
        // Lấy danh sách ingredient_id mà user có
        $userIngredientIds = UserIngredient::where('user_id', $user->id)
            ->whereHas('ingredient')
            ->pluck('ingredient_id')
            ->toArray();

        if (empty($userIngredientIds)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa có nguyên liệu nào trong tủ lạnh!',
                ], 400);
            }
            return redirect()->route('user.ingredients.index')
                ->with('error', 'Bạn chưa có nguyên liệu nào trong tủ lạnh!');
        }

        // Lấy tất cả món ăn có chứa ít nhất một nguyên liệu mà user có
        $dishes = Dish::where('status', 'active')
            ->whereHas('ingredients', function($query) use ($userIngredientIds) {
                $query->whereIn('ingredients.id', $userIngredientIds);
            })
            ->with(['ingredients' => function($query) {
                $query->select('ingredients.id', 'ingredients.name', 'ingredients.type');
            }])
            ->with('category')
            ->get();

        // Tính toán tỷ lệ nguyên liệu đủ cho mỗi món
        $dishesWithMatchRate = $dishes->map(function($dish) use ($userIngredientIds) {
            $dishIngredientIds = $dish->ingredients->pluck('id')->toArray();
            $matchedIngredients = array_intersect($dishIngredientIds, $userIngredientIds);
            $totalIngredients = count($dishIngredientIds);
            $matchedCount = count($matchedIngredients);
            $matchRate = $totalIngredients > 0 ? ($matchedCount / $totalIngredients) * 100 : 0;
            
            // Lấy nguyên liệu còn thiếu
            $missingIngredientIds = array_diff($dishIngredientIds, $userIngredientIds);
            $missingIngredients = Ingredient::whereIn('id', $missingIngredientIds)->get(['id', 'name', 'type']);

            return [
                'dish' => $dish,
                'matched_count' => $matchedCount,
                'total_count' => $totalIngredients,
                'match_rate' => round($matchRate, 1),
                'missing_ingredients' => $missingIngredients,
            ];
        })->sortByDesc('match_rate');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $dishesWithMatchRate->values(),
            ]);
        }

        return view('customer.ingredients.dishes', [
            'dishesWithMatchRate' => $dishesWithMatchRate,
            'userIngredientIds' => $userIngredientIds,
        ]);
    }
}
