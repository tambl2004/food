<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Category::withCount(['products', 'dishes'])->latest();

        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $status = (int)$request->status;
            $query->where('status', $status);
        }

        // Lọc theo meal_type
        if ($request->has('meal_type') && $request->meal_type != '') {
            $query->where('meal_type', $request->meal_type);
        }

        // Lọc theo diet_type
        if ($request->has('diet_type') && $request->diet_type != '') {
            $query->where('diet_type', $request->diet_type);
        }

        $categories = $query->paginate(15);

        // Thống kê
        $totalCategories = Category::count();
        $visibleCategories = Category::where('status', 1)->count();
        $hiddenCategories = Category::where('status', 0)->count();

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'visibleCategories',
            'hiddenCategories'
        ));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'meal_time' => 'nullable|array',
            'meal_time.*' => 'string',
            'meal_type' => 'nullable|string|max:255',
            'diet_type' => 'nullable|string|max:255',
            'status' => 'nullable|in:0,1',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'meal_time' => $validated['meal_time'] ?? null,
            'meal_type' => $validated['meal_type'] ?? null,
            'diet_type' => $validated['diet_type'] ?? null,
            'status' => isset($validated['status']) ? (int)$validated['status'] : 1,
        ];

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Tạo danh mục thành công!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'meal_time' => 'nullable|array',
            'meal_time.*' => 'string',
            'meal_type' => 'nullable|string|max:255',
            'diet_type' => 'nullable|string|max:255',
            'status' => 'nullable|in:0,1',
        ], [
            'name.required' => 'Tên danh mục là bắt buộc.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'meal_time' => $validated['meal_time'] ?? null,
            'meal_type' => $validated['meal_type'] ?? null,
            'diet_type' => $validated['diet_type'] ?? null,
            'status' => isset($validated['status']) ? (int)$validated['status'] : $category->status,
        ];

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }

    /**
     * Cập nhật trạng thái danh mục (Ẩn / Hiện)
     */
    public function updateStatus(Request $request, Category $category)
    {
        $validated = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $category->update(['status' => (int)$validated['status']]);

        $message = $validated['status'] == 1 
            ? 'Đã hiển thị danh mục thành công!' 
            : 'Đã ẩn danh mục thành công!';

        return back()->with('success', $message);
    }
}