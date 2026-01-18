<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    /**
     * Hiển thị danh sách tất cả đánh giá
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();
        
        // Lọc theo trạng thái duyệt
        if ($request->has('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }
        
        // Lọc theo rating
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }
        
        // Tìm kiếm theo tên sản phẩm hoặc tên user
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        
        $reviews = $query->paginate(15);
        
        return view('admin.reviews.index', compact('reviews'));
    }
    
    /**
     * Hiển thị chi tiết đánh giá
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }
    
    /**
     * Duyệt đánh giá
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        
        return redirect()->back()->with('success', 'Đánh giá đã được duyệt!');
    }
    
    /**
     * Từ chối đánh giá
     */
    public function reject(Review $review)
    {
        $review->update(['is_approved' => false]);
        
        return redirect()->back()->with('success', 'Đánh giá đã bị từ chối!');
    }
    
    /**
     * Xóa đánh giá
     */
    public function destroy(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')
                        ->with('success', 'Đánh giá đã được xóa!');
    }
    
    /**
     * Thống kê đánh giá
     */
    public function statistics()
    {
        $totalReviews = Review::count();
        $approvedReviews = Review::where('is_approved', true)->count();
        $pendingReviews = Review::where('is_approved', false)->count();
        
        // Thống kê theo rating
        $ratingStats = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingStats[$i] = Review::where('rating', $i)->where('is_approved', true)->count();
        }
        
        // Top sản phẩm có đánh giá cao nhất
        $topProducts = Product::withCount(['approvedReviews'])
            ->withAvg('approvedReviews as avg_rating', 'rating')
            ->having('approved_reviews_count', '>', 0)
            ->orderBy('avg_rating', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reviews.statistics', compact(
            'totalReviews', 'approvedReviews', 'pendingReviews', 
            'ratingStats', 'topProducts'
        ));
    }
}
