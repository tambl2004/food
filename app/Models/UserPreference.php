<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favorite_categories',
        'origins',
        'diet_type',
        'diet_types',
        'spicy_level',
        'disliked_ingredients',
        'allergies',
        'health_goal',
    ];

    protected function casts(): array
    {
        return [
            'favorite_categories' => 'array',
            'origins' => 'array',
            'diet_types' => 'array',
            'disliked_ingredients' => 'array',
            'allergies' => 'array',
            'spicy_level' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
