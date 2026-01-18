<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Lưu đánh giá mới của khách hàng
     */
    public function store(Request $request, Product $product)
    {
        // Kiểm tra xem user đã đánh giá sản phẩm này chưa
        $existingReview = Review::where('user_id', Auth::id())
                               ->where('product_id', $product->id)
                               ->first();
        
        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá món ăn này rồi!');
        }
        
        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
            'rating.min' => 'Số sao tối thiểu là 1',
            'rating.max' => 'Số sao tối đa là 5',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        // Kiểm tra nếu có dish_id (đánh giá cho Dish)
        if ($request->has('dish_id')) {
            $dish = Dish::findOrFail($request->dish_id);
            
            // Kiểm tra user đã đánh giá dish này chưa
            $existingDishReview = Review::where('user_id', Auth::id())
                                       ->where('dish_id', $dish->id)
                                       ->first();
            
            if ($existingDishReview) {
                return redirect()->back()->with('error', 'Bạn đã đánh giá món ăn này rồi!');
            }

            // Tạo đánh giá cho Dish
            Review::create([
                'user_id' => Auth::id(),
                'dish_id' => $dish->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'visible', // Dish reviews dùng status
                'is_approved' => true
            ]);

            return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
        }

        // Tạo đánh giá mới cho Product (logic cũ)
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true // Tự động duyệt
        ]);
        
        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }
    
    /**
     * Cập nhật đánh giá
     */
    public function update(Request $request, Review $review)
    {
        // Kiểm tra quyền sửa đánh giá
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bản không có quyền sửa đánh giá này!');
        }
        
        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }
        
        // Cập nhật đánh giá
        $updateData = [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        // Nếu là review cho Dish, cập nhật status
        if ($review->dish_id) {
            $updateData['status'] = 'visible';
        } else {
            $updateData['is_approved'] = true;
        }

        $review->update($updateData);
        
        return redirect()->back()->with('success', 'Đánh giá đã được cập nhật!');
    }
    
    /**
     * Xóa đánh giá
     */
    public function destroy(Review $review)
    {
        // Kiểm tra quyền xóa đánh giá
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bản không có quyền xóa đánh giá này!');
        }
        
        $review->delete();
        
        return redirect()->back()->with('success', 'Đánh giá đã được xóa!');
    }

    /**
     * Lưu đánh giá mới cho Dish
     */
    public function storeDish(Request $request, Dish $dish)
    {
        // Kiểm tra xem user đã đánh giá dish này chưa
        $existingReview = Review::where('user_id', Auth::id())
                               ->where('dish_id', $dish->id)
                               ->first();
        
        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá món ăn này rồi!');
        }
        
        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá',
            'rating.min' => 'Số sao tối thiểu là 1',
            'rating.max' => 'Số sao tối đa là 5',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Tạo đánh giá cho Dish
        Review::create([
            'user_id' => Auth::id(),
            'dish_id' => $dish->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'visible', // Dish reviews dùng status
            'is_approved' => true
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }
    
}
