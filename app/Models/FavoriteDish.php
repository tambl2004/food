<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteDish extends Model
{
    use HasFactory;

    // Tắt updated_at vì bảng chỉ có created_at
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relationship với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship với Product (món ăn)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

