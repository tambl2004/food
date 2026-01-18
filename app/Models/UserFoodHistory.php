<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFoodHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dish_id',
        'action',
        'action_at',
    ];

    protected $casts = [
        'action_at' => 'datetime',
    ];

    /**
     * Relationship với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với Dish
     */
    public function dish()
    {
        return $this->belongsTo(Dish::class);
    }
}
