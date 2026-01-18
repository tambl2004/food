<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'product_id',
        'dish_id',
        'order_id',
        'order_item_id',
        'rating',
        'comment',
        'is_approved',
        'status'
    ];
    
    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer'
    ];
    
    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relationship với Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship với Dish
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
    
    // Relationship với Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    
    // Relationship với OrderItem
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
    
    // Scope để lấy các review đã được duyệt (cho Product reviews)
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
    
    // Scope để lấy review theo rating
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Scope để lấy các review visible (cho Dish reviews)
    public function scopeVisible($query)
    {
        return $query->where('status', 'visible');
    }

    // Scope để lấy các review hidden (cho Dish reviews)
    public function scopeHidden($query)
    {
        return $query->where('status', 'hidden');
    }

    // Scope để lấy chỉ Dish reviews
    public function scopeDishReviews($query)
    {
        return $query->whereNotNull('dish_id');
    }

    // Scope để lấy chỉ Product reviews
    public function scopeProductReviews($query)
    {
        return $query->whereNotNull('product_id');
    }
}
