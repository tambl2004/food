<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Hiển thị danh sách tin tức
     */
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Hiển thị form tạo tin tức
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Lưu tin tức mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
            'author' => 'required|string|max:100',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        
        // Tạo slug từ title
        $data['slug'] = Str::slug($request->title);
        
        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/news'), $imageName);
            $data['image'] = 'images/news/' . $imageName;
        }

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Tin tức đã được tạo thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa tin tức
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Cập nhật tin tức
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url',
            'author' => 'required|string|max:100',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $data = $request->all();
        
        // Cập nhật slug nếu title thay đổi
        if ($request->title !== $news->title) {
            $data['slug'] = Str::slug($request->title);
        }
        
        // Xử lý upload hình ảnh mới
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ
            if ($news->image && file_exists(public_path($news->image))) {
                unlink(public_path($news->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/news'), $imageName);
            $data['image'] = 'images/news/' . $imageName;
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Tin tức đã được cập nhật thành công!');
    }

    /**
     * Xóa tin tức
     */
    public function destroy(News $news)
    {
        // Xóa hình ảnh
        if ($news->image && file_exists(public_path($news->image))) {
            unlink(public_path($news->image));
        }
        
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Tin tức đã được xóa thành công!');
    }
}