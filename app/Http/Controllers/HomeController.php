<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\News;
use App\Models\Faq;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Lấy sản phẩm nổi bật với thông tin đánh giá
        $featuredProducts = Product::withCount(['approvedReviews as review_count'])
            ->withAvg('approvedReviews as average_rating', 'rating')
            ->latest()
            ->take(8)
            ->get();
        
        // Lấy tin tức nổi bật
        $featuredNews = News::published()
            ->featured()
            ->latest()
            ->take(3)
            ->get();
        
        // Nếu chưa migrate bảng FAQ, tránh lỗi tại trang chủ
        $faqs = collect();
        if (Schema::hasTable('faqs')) {
            $faqs = Faq::where('is_active', true)
                ->orderBy('display_order')
                ->take(8)
                ->get();
        }
        
        return view('customer.home', compact('featuredProducts', 'featuredNews', 'faqs'));
    }
}