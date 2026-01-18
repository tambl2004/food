<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'unit',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Relationship với Dishes qua dish_ingredients
     */
    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_ingredients')
                    ->withPivot('quantity', 'unit', 'is_required')
                    ->withTimestamps();
    }

    /**
     * Relationship với IngredientNutrition (one-to-one)
     */
    public function nutrition()
    {
        return $this->hasOne(IngredientNutrition::class);
    }

    /**
     * Relationship với User qua UserIngredient
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_ingredients')
                    ->withPivot('quantity', 'unit', 'updated_at')
                    ->withTimestamps();
    }

    /**
     * Scope để lấy nguyên liệu active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope để lấy nguyên liệu inactive
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
