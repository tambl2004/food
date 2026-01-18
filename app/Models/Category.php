<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Cho phép gán giá trị hàng loạt cho các cột này
    protected $fillable = ['name', 'slug', 'description', 'meal_time', 'status', 'meal_type', 'diet_type'];

    protected $casts = [
        'meal_time' => 'array',
        'status' => 'integer',
    ];

    // Định nghĩa mối quan hệ với Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Định nghĩa mối quan hệ với Dish
    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    /**
     * Scope để lấy danh mục hiển thị (không ẩn)
     */
    public function scopeVisible($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope để lấy danh mục ẩn
     */
    public function scopeHidden($query)
    {
        return $query->where('status', 0);
    }
}