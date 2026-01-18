<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
     // Cho phép gán giá trị hàng loạt cho các cột chính của sản phẩm
    protected $fillable = ['name', 'description', 'image', 'category_id', 'origin', 'video_url', 'difficulty', 'prep_time', 'cook_time', 'servings'];
     
     // Thêm vào trong class Product
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // Relationship với Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    // Lấy các review đã được duyệt
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }
    
    // Tính điểm trung bình của sản phẩm
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }
    
    // Đếm tổng số review
    public function getReviewCountAttribute()
    {
        return $this->approvedReviews()->count();
    }
    
    // Lấy phân bố sao (1-5 sao)
    public function getRatingDistributionAttribute()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $this->approvedReviews()->where('rating', $i)->count();
        }
        return $distribution;
    }

    // Relationship với FavoriteDish
    public function favoriteDishes()
    {
        return $this->hasMany(FavoriteDish::class);
    }

    // Relationship với User qua FavoriteDish
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_dishes', 'product_id', 'user_id')
                    ->withPivot('created_at');
    }

    // Note: Products no longer have a price field (removed in migration 2026_01_03_000400_update_products_fields)
    // Products are now recipes, not purchasable items
}
