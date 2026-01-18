<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use App\Models\IngredientNutrition;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IngredientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of the ingredients.
     */
    public function index(Request $request)
    {
        $query = Ingredient::withCount('dishes')->latest();

        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Lọc theo loại nguyên liệu
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $ingredients = $query->paginate(15);

        // Thống kê
        $totalIngredients = Ingredient::count();
        $activeIngredients = Ingredient::where('status', 'active')->count();
        $inactiveIngredients = Ingredient::where('status', 'inactive')->count();

        // Lấy danh sách các loại nguyên liệu để filter
        $ingredientTypes = Ingredient::distinct()->whereNotNull('type')->pluck('type')->sort()->values();

        return view('admin.ingredients.index', compact(
            'ingredients',
            'totalIngredients',
            'activeIngredients',
            'inactiveIngredients',
            'ingredientTypes'
        ));
    }

    /**
     * Show the form for creating a new ingredient.
     */
    public function create()
    {
        return view('admin.ingredients.create');
    }

    /**
     * Store a newly created ingredient.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            // Nutrition fields
            'calories' => 'nullable|numeric|min:0',
            'protein' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'vitamins' => 'nullable|string',
        ], [
            'name.required' => 'Tên nguyên liệu là bắt buộc.',
            'name.unique' => 'Tên nguyên liệu đã tồn tại.',
            'type.required' => 'Loại nguyên liệu là bắt buộc.',
        ]);

        // Tạo nguyên liệu
        $ingredient = Ingredient::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        // Tạo thông tin dinh dưỡng nếu có
        if (isset($validated['calories']) || isset($validated['protein']) || isset($validated['fat']) || 
            isset($validated['carbs']) || isset($validated['fiber']) || isset($validated['vitamins'])) {
            IngredientNutrition::create([
                'ingredient_id' => $ingredient->id,
                'calories' => $validated['calories'] ?? null,
                'protein' => $validated['protein'] ?? null,
                'fat' => $validated['fat'] ?? null,
                'carbs' => $validated['carbs'] ?? null,
                'fiber' => $validated['fiber'] ?? null,
                'vitamins' => $validated['vitamins'] ?? null,
            ]);
        }

        return redirect()->route('admin.ingredients.index')->with('success', 'Tạo nguyên liệu thành công!');
    }

    /**
     * Display the specified ingredient.
     */
    public function show(Ingredient $ingredient)
    {
        $ingredient->load(['nutrition', 'dishes' => function($query) {
            $query->with('category')->latest()->take(10);
        }]);

        // Thống kê
        $usedInDishesCount = $ingredient->dishes()->count();
        $cameraScanCount = 0; // Tạm thời, có thể thêm sau khi có bảng scan_history
        $userHaveCount = 0; // Tạm thời, có thể thêm sau khi có bảng user_ingredients

        return view('admin.ingredients.show', compact(
            'ingredient',
            'usedInDishesCount',
            'cameraScanCount',
            'userHaveCount'
        ));
    }

    /**
     * Show the form for editing the specified ingredient.
     */
    public function edit(Ingredient $ingredient)
    {
        $ingredient->load('nutrition');
        return view('admin.ingredients.edit', compact('ingredient'));
    }

    /**
     * Update the specified ingredient.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $ingredient->id,
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
            // Nutrition fields
            'calories' => 'nullable|numeric|min:0',
            'protein' => 'nullable|numeric|min:0',
            'fat' => 'nullable|numeric|min:0',
            'carbs' => 'nullable|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'vitamins' => 'nullable|string',
        ], [
            'name.required' => 'Tên nguyên liệu là bắt buộc.',
            'name.unique' => 'Tên nguyên liệu đã tồn tại.',
            'type.required' => 'Loại nguyên liệu là bắt buộc.',
        ]);

        // Cập nhật nguyên liệu
        $ingredient->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? $ingredient->status,
        ]);

        // Cập nhật hoặc tạo thông tin dinh dưỡng
        $nutritionData = [
            'calories' => $validated['calories'] ?? null,
            'protein' => $validated['protein'] ?? null,
            'fat' => $validated['fat'] ?? null,
            'carbs' => $validated['carbs'] ?? null,
            'fiber' => $validated['fiber'] ?? null,
            'vitamins' => $validated['vitamins'] ?? null,
        ];

        if ($ingredient->nutrition) {
            $ingredient->nutrition->update($nutritionData);
        } else {
            IngredientNutrition::create(array_merge($nutritionData, ['ingredient_id' => $ingredient->id]));
        }

        return redirect()->route('admin.ingredients.index')->with('success', 'Cập nhật nguyên liệu thành công!');
    }

    /**
     * Remove the specified ingredient.
     * Note: Theo yêu cầu, không cho xóa nguyên liệu đang dùng trong món
     */
    public function destroy(Ingredient $ingredient)
    {
        // Kiểm tra xem nguyên liệu có đang được sử dụng trong món nào không
        if ($ingredient->dishes()->count() > 0) {
            return redirect()->route('admin.ingredients.index')
                ->with('error', 'Không thể xóa nguyên liệu này vì đang được sử dụng trong các món ăn. Vui lòng ẩn thay vì xóa.');
        }

        $ingredient->delete();
        return redirect()->route('admin.ingredients.index')->with('success', 'Xóa nguyên liệu thành công!');
    }

    /**
     * Cập nhật trạng thái nguyên liệu (Ẩn / Hiện)
     */
    public function updateStatus(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $ingredient->update(['status' => $validated['status']]);

        $message = $validated['status'] == 'active' 
            ? 'Đã hiển thị nguyên liệu thành công!' 
            : 'Đã ẩn nguyên liệu thành công!';

        return back()->with('success', $message);
    }
}