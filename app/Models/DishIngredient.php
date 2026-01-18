<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishIngredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'dish_id',
        'ingredient_id',
        'quantity',
        'unit',
        'is_required',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    /**
     * Relationship với Dish
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }

    /**
     * Relationship với Ingredient
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}

