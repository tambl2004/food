<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientNutrition extends Model
{
    use HasFactory;

    protected $table = 'ingredient_nutrition';

    protected $fillable = [
        'ingredient_id',
        'calories',
        'protein',
        'fat',
        'carbs',
        'fiber',
        'vitamins',
    ];

    protected $casts = [
        'calories' => 'float',
        'protein' => 'float',
        'fat' => 'float',
        'carbs' => 'float',
        'fiber' => 'float',
    ];

    /**
     * Relationship với Ingredient (belongs to)
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}