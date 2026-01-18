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
        'diet_type',
        'spicy_level',
        'disliked_ingredients',
        'health_goal',
    ];

    protected function casts(): array
    {
        return [
            'favorite_categories' => 'array',
            'disliked_ingredients' => 'array',
            'spicy_level' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
