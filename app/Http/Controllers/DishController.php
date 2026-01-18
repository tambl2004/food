<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Product;
use App\Models\UserFoodHistory;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Display the specified dish.
     */
    public function show(Request $request, Dish $dish)
    {
        // Kiểm tra dish có active không
        if ($dish->status !== 'active') {
            abort(404);
        }

        // Load relationships
        $dish->load(['category', 'ingredients', 'visibleReviews.user']);

        // Log view action (if user is authenticated)
        if ($request->user()) {
            // Chỉ log một lần trong 24h
            $existing = UserFoodHistory::where('user_id', $request->user()->id)
                ->where('dish_id', $dish->id)
                ->where('action', 'viewed')
                ->where('action_at', '>=', now()->subDay())
                ->first();

            if (!$existing) {
                UserFoodHistory::create([
                    'user_id' => $request->user()->id,
                    'dish_id' => $dish->id,
                    'action' => 'viewed',
                    'action_at' => now(),
                ]);
            }
        }

        // Tìm Product tương ứng (nếu có) để hiển thị thông tin tương tự
        $product = Product::where('name', $dish->name)->first();

        // Lấy các dish liên quan (cùng category)
        $relatedDishes = Dish::where('status', 'active')
            ->where('category_id', $dish->category_id)
            ->where('id', '!=', $dish->id)
            ->limit(4)
            ->get();

        return view('customer.dishes.show', [
            'dish' => $dish,
            'product' => $product,
            'relatedDishes' => $relatedDishes,
        ]);
    }
}
