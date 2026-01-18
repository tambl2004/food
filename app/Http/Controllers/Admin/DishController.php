<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\DishIngredient;
use App\Models\FavoriteDish;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DishController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Hiển thị danh sách món ăn
     */
    public function index(Request $request)
    {
        $query = Dish::with('category')->latest('created_at');

        // Tìm kiếm theo tên món
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Lọc theo độ khó
        if ($request->has('difficulty') && $request->difficulty != '') {
            $query->where('difficulty', $request->difficulty);
        }

        // Lọc theo danh mục
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo nguồn gốc
        if ($request->has('origin') && $request->origin != '') {
            $query->where('origin', 'like', '%' . $request->origin . '%');
        }

        $dishes = $query->paginate(15);

        // Thống kê
        $totalDishes = Dish::count();
        $activeDishes = Dish::where('status', 'active')->count();
        $inactiveDishes = Dish::where('status', 'inactive')->count();

        // Lấy danh sách categories và origins cho filter
        $categories = Category::all();
        $origins = Dish::whereNotNull('origin')
                      ->distinct()
                      ->pluck('origin')
                      ->filter()
                      ->sort()
                      ->values();

        return view('admin.dishes.index', compact(
            'dishes', 
            'totalDishes', 
            'activeDishes', 
            'inactiveDishes',
            'categories',
            'origins'
        ));
    }

    /**
     * Hiển thị form tạo món ăn mới
     */
    public function create()
    {
        $categories = Category::all();
        $ingredients = Ingredient::orderBy('name')->get();
        
        return view('admin.dishes.create', compact('categories', 'ingredients'));
    }

    /**
     * Lưu món ăn mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'origin' => 'nullable|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'required|integer|min:1',
            'servings' => 'nullable|integer|min:1',
            'calories' => 'nullable|integer|min:0',
            'image' => 'nullable|string|max:255',
            'video_url' => 'nullable|url|max:500',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'nullable|string|max:50',
            'ingredients.*.unit' => 'nullable|string|max:20',
            'ingredients.*.is_required' => 'boolean',
        ], [
            'name.required' => 'Tên món ăn là bắt buộc.',
            'description.required' => 'Mô tả là bắt buộc.',
            'cook_time.required' => 'Thời gian nấu là bắt buộc.',
            'cook_time.min' => 'Thời gian nấu phải lớn hơn 0.',
            'ingredients.required' => 'Món ăn phải có ít nhất 1 nguyên liệu.',
            'ingredients.min' => 'Món ăn phải có ít nhất 1 nguyên liệu.',
        ]);

        // Kiểm tra trùng tên + danh mục
        $existingDish = Dish::where('name', $validated['name'])
                           ->where('category_id', $validated['category_id'])
                           ->first();

        if ($existingDish) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Món ăn với tên này đã tồn tại trong danh mục này.']);
        }

        // Tạo slug từ name
        $slug = Str::slug($validated['name']);
        $validated['slug'] = $slug;

        // Tạo món ăn
        $dish = Dish::create($validated);

        // Lưu nguyên liệu
        foreach ($request->ingredients as $ingredientData) {
            DishIngredient::create([
                'dish_id' => $dish->id,
                'ingredient_id' => $ingredientData['ingredient_id'],
                'quantity' => $ingredientData['quantity'] ?? null,
                'unit' => $ingredientData['unit'] ?? null,
                'is_required' => $ingredientData['is_required'] ?? true,
            ]);
        }

        return redirect()
            ->route('admin.dishes.index')
            ->with('success', 'Tạo món ăn mới thành công!');
    }

    /**
     * Hiển thị chi tiết món ăn
     */
    public function show(Dish $dish)
    {
        $dish->load(['category', 'ingredients', 'reviews.user']);
        
        // Tính toán thống kê
        $statistics = [
            'ingredient_count' => $dish->ingredients()->count(),
            'review_count' => $dish->review_count,
            'average_rating' => $dish->average_rating,
            'cook_count' => $dish->cook_count,
        ];

        return view('admin.dishes.show', compact('dish', 'statistics'));
    }

    /**
     * Hiển thị form chỉnh sửa món ăn
     */
    public function edit(Dish $dish)
    {
        $dish->load('ingredients');
        $categories = Category::all();
        $ingredients = Ingredient::orderBy('name')->get();
        
        return view('admin.dishes.edit', compact('dish', 'categories', 'ingredients'));
    }

    /**
     * Cập nhật món ăn
     */
    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'origin' => 'nullable|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'required|integer|min:1',
            'servings' => 'nullable|integer|min:1',
            'calories' => 'nullable|integer|min:0',
            'image' => 'nullable|string|max:255',
            'video_url' => 'nullable|url|max:500',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'nullable|string|max:50',
            'ingredients.*.unit' => 'nullable|string|max:20',
            'ingredients.*.is_required' => 'boolean',
        ], [
            'name.required' => 'Tên món ăn là bắt buộc.',
            'description.required' => 'Mô tả là bắt buộc.',
            'cook_time.required' => 'Thời gian nấu là bắt buộc.',
            'cook_time.min' => 'Thời gian nấu phải lớn hơn 0.',
            'ingredients.required' => 'Món ăn phải có ít nhất 1 nguyên liệu.',
            'ingredients.min' => 'Món ăn phải có ít nhất 1 nguyên liệu.',
        ]);

        // Kiểm tra trùng tên + danh mục (trừ chính nó)
        $existingDish = Dish::where('name', $validated['name'])
                           ->where('category_id', $validated['category_id'])
                           ->where('id', '!=', $dish->id)
                           ->first();

        if ($existingDish) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Món ăn với tên này đã tồn tại trong danh mục này.']);
        }

        // Cập nhật slug nếu name thay đổi
        if ($dish->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Cập nhật món ăn
        $dish->update($validated);

        // Xóa nguyên liệu cũ
        $dish->dishIngredients()->delete();

        // Lưu nguyên liệu mới
        foreach ($request->ingredients as $ingredientData) {
            DishIngredient::create([
                'dish_id' => $dish->id,
                'ingredient_id' => $ingredientData['ingredient_id'],
                'quantity' => $ingredientData['quantity'] ?? null,
                'unit' => $ingredientData['unit'] ?? null,
                'is_required' => $ingredientData['is_required'] ?? true,
            ]);
        }

        return redirect()
            ->route('admin.dishes.index')
            ->with('success', 'Cập nhật món ăn thành công!');
    }

    /**
     * Cập nhật trạng thái món ăn (Ẩn / Hiện)
     */
    public function updateStatus(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $dish->update(['status' => $validated['status']]);

        $message = $validated['status'] === 'active' 
            ? 'Đã hiển thị món ăn thành công!' 
            : 'Đã ẩn món ăn thành công!';

        return back()->with('success', $message);
    }
}

