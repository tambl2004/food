<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Hiển thị danh sách tin tức
     */
    public function index()
    {
        $news = News::published()
            ->latest()
            ->paginate(9);

        return view('customer.news.index', compact('news'));
    }

    /**
     * Hiển thị chi tiết tin tức
     */
    public function show($slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Tăng số lượt xem
        $news->incrementViews();

        // Lấy tin tức liên quan
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('customer.news.show', compact('news', 'relatedNews'));
    }
}