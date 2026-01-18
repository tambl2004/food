<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'origin',
        'description',
        'difficulty',
        'prep_time',
        'cook_time',
        'servings',
        'calories',
        'image',
        'video_url',
        'status',
    ];

    protected $casts = [
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'servings' => 'integer',
        'calories' => 'integer',
    ];

    /**
     * Boot method để tự động tạo slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dish) {
            if (empty($dish->slug)) {
                $dish->slug = Str::slug($dish->name);
            }
        });

        static::updating(function ($dish) {
            if ($dish->isDirty('name') && empty($dish->slug)) {
                $dish->slug = Str::slug($dish->name);
            }
        });
    }

    /**
     * Relationship với Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship với Ingredients qua dish_ingredients
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'dish_ingredients')
                    ->withPivot('quantity', 'unit', 'is_required')
                    ->withTimestamps();
    }

    /**
     * Relationship với dish_ingredients pivot table
     */
    public function dishIngredients()
    {
        return $this->hasMany(DishIngredient::class);
    }

    /**
     * Relationship với Reviews (Dish reviews)
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'dish_id');
    }

    /**
     * Lấy các review đã được duyệt (cho Product reviews - tương thích ngược)
     */
    public function approvedReviews()
    {
        return $this->reviews()->where('is_approved', true);
    }

    /**
     * Lấy các review visible (cho Dish reviews)
     */
    public function visibleReviews()
    {
        return $this->reviews()->where('status', 'visible');
    }

    /**
     * Tính điểm trung bình của món ăn (sử dụng visible reviews)
     */
    public function getAverageRatingAttribute()
    {
        return $this->visibleReviews()->avg('rating') ?: 0;
    }

    /**
     * Đếm tổng số review visible
     */
    public function getReviewCountAttribute()
    {
        return $this->visibleReviews()->count();
    }

    /**
     * Đếm số lượt nấu (từ favorite_dishes hoặc cook_history nếu có)
     */
    public function getCookCountAttribute()
    {
        // Tạm thời trả về số lượt yêu thích
        // Có thể mở rộng sau với bảng cook_history
        return FavoriteDish::where('product_id', $this->id)->count();
    }

    /**
     * Scope để lấy món active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope để lấy món inactive
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Lấy YouTube video ID từ URL
     */
    public function getYoutubeVideoIdAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        $url = $this->video_url;
        
        // Hỗ trợ các format URL YouTube:
        // https://www.youtube.com/watch?v=VIDEO_ID
        // https://youtu.be/VIDEO_ID
        // https://www.youtube.com/embed/VIDEO_ID
        // https://m.youtube.com/watch?v=VIDEO_ID
        
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|m\.youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Lấy YouTube embed URL
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        $videoId = $this->youtube_video_id;
        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }
        return null;
    }

    /**
     * Relationship với UserFoodHistory
     */
    public function userFoodHistories()
    {
        return $this->hasMany(UserFoodHistory::class);
    }

    /**
     * Relationship với User qua UserFoodHistory
     */
    public function viewedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_food_histories')
                    ->wherePivot('action', 'viewed')
                    ->withPivot('action_at')
                    ->withTimestamps();
    }

    /**
     * Relationship với User qua UserFoodHistory (cooked)
     */
    public function cookedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_food_histories')
                    ->wherePivot('action', 'cooked')
                    ->withPivot('action_at')
                    ->withTimestamps();
    }
}

