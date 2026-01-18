<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->paginate(10); // Sửa '›' thành '->'
        return view('admin.categories.index', compact('categories'));
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
            'status' => 'nullable|in:0,1',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'meal_time' => $validated['meal_time'] ?? null,
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
            'status' => 'nullable|in:0,1',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'meal_time' => $validated['meal_time'] ?? null,
            'status' => isset($validated['status']) ? (int)$validated['status'] : $category->status,
        ];

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Category $category)
    {
        $category->delete(); // Sửa '›' thành '->'
        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!'); // Sửa '›' thành '->'
    }
}