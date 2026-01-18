<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // <--- DÒNG QUAN TRỌNG BẠN ĐÃ NÓI
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);

        // Global stats (counts across all products) — avoid counting only the current page
        $totalProducts = Product::count();
        $easyCount = Product::where('difficulty', 'easy')->count();
        $mediumCount = Product::where('difficulty', 'medium')->count();
        $hardCount = Product::where('difficulty', 'hard')->count();

        return view('admin.products.index', compact('products', 'totalProducts', 'easyCount', 'mediumCount', 'hardCount'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'origin' => 'nullable|string|max:255',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|url|max:500',
            'video_url' => 'nullable|url|max:1000',
        ]);

        // Lưu trực tiếp URL ảnh từ internet
        if ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        if ($request->filled('video_url')) {
            $validated['video_url'] = $request->video_url;
        }

        if ($request->filled('origin')) {
            $validated['origin'] = $request->origin;
        }

        // Optional recipe fields
        if ($request->filled('difficulty')) $validated['difficulty'] = $request->difficulty;
        if ($request->filled('prep_time')) $validated['prep_time'] = (int) $request->prep_time;
        if ($request->filled('cook_time')) $validated['cook_time'] = (int) $request->cook_time;
        if ($request->filled('servings')) $validated['servings'] = (int) $request->servings;

        Product::create($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    // --- HÀM MỚI ---
    // Hiển thị form để sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // --- HÀM MỚI ---
    // Cập nhật sản phẩm trong database
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'origin' => 'nullable|string|max:255',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'prep_time' => 'nullable|integer|min:0',
            'cook_time' => 'nullable|integer|min:0',
            'servings' => 'nullable|integer|min:1',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|url|max:500',
            'video_url' => 'nullable|url|max:1000',
        ]);

        // Lưu trực tiếp URL ảnh từ internet
        if ($request->filled('image')) {
            $validated['image'] = $request->image;
        }

        if ($request->filled('video_url')) {
            $validated['video_url'] = $request->video_url;
        }

        if ($request->filled('origin')) {
            $validated['origin'] = $request->origin;
        }

        if ($request->filled('difficulty')) $validated['difficulty'] = $request->difficulty;
        if ($request->filled('prep_time')) $validated['prep_time'] = (int) $request->prep_time;
        if ($request->filled('cook_time')) $validated['cook_time'] = (int) $request->cook_time;
        if ($request->filled('servings')) $validated['servings'] = (int) $request->servings;

        $product->update($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    // --- HÀM MỚI ---
    // Xóa sản phẩm khỏi database
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
                         ->with('success', 'Sản phẩm đã được xóa thành công!');
    }
}