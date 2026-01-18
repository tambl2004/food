<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Dish;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }
    
    /**
     * Hiển thị danh sách tất cả đánh giá
     * Quản lý cả Product reviews và Dish reviews
     */
    public function index(Request $request)
    {
        // Mặc định chỉ lấy Dish reviews (theo yêu cầu quản lý đánh giá & feedback cho Dishes)
        $query = Review::with(['user', 'product', 'dish'])
            ->dishReviews()
            ->latest();
        
        // Lọc theo trạng thái (visible/hidden cho Dish reviews)
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'visible') {
                $query->where('status', 'visible');
            } elseif ($request->status === 'hidden') {
                $query->where('status', 'hidden');
            }
        }
        
        // Lọc theo rating (1-5)
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }
        
        // Lọc theo món ăn
        if ($request->has('dish_id') && $request->dish_id != '') {
            $query->where('dish_id', $request->dish_id);
        }
        
        // Tìm kiếm theo nội dung comment hoặc tên user
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('dish', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $reviews = $query->paginate(15);
        
        // Lấy danh sách món ăn để filter
        $dishes = Dish::orderBy('name')->get(['id', 'name']);
        
        // Thống kê
        $totalReviews = Review::dishReviews()->count();
        $visibleReviews = Review::dishReviews()->where('status', 'visible')->count();
        $hiddenReviews = Review::dishReviews()->where('status', 'hidden')->count();
        
        return view('admin.reviews.index', compact('reviews', 'dishes', 'totalReviews', 'visibleReviews', 'hiddenReviews'));
    }
    
    /**
     * Hiển thị chi tiết đánh giá
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product', 'dish']);
        
        // Kiểm tra xem user đã nấu món này chưa (từ favorite_dishes hoặc cook_history)
        $hasCooked = false;
        $cookedAt = null;
        
        if ($review->dish_id && $review->user_id) {
            // Tạm thời kiểm tra từ favorite_dishes, có thể mở rộng sau với cook_history
            $favorite = \App\Models\FavoriteDish::where('user_id', $review->user_id)
                ->where('product_id', $review->dish_id)
                ->first();
            
            if ($favorite) {
                $hasCooked = true;
                $cookedAt = $favorite->created_at;
            }
        }
        
        return view('admin.reviews.show', compact('review', 'hasCooked', 'cookedAt'));
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
     * Cập nhật trạng thái đánh giá (visible/hidden) - cho Dish reviews
     */
    public function updateStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:visible,hidden',
        ]);

        // Chỉ cho phép cập nhật status cho Dish reviews
        if (!$review->dish_id) {
            return back()->with('error', 'Chức năng này chỉ dành cho đánh giá món ăn!');
        }

        $review->update(['status' => $validated['status']]);

        $message = $validated['status'] == 'visible' 
            ? 'Đã hiển thị đánh giá thành công!' 
            : 'Đã ẩn đánh giá thành công!';

        return back()->with('success', $message);
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
