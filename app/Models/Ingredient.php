<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'unit',
        'description',
        'slug'
    ];

    // No stock field - ingredients are just reference data

    /**
     * Relationship với Dishes qua dish_ingredients
     */
    public function dishes()
    {
        return $this->belongsToMany(Dish::class, 'dish_ingredients')
                    ->withPivot('quantity', 'unit', 'is_required')
                    ->withTimestamps();
    }
}
