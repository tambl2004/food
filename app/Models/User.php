<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail // <-- SỬA DÒNG NÀY
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Thêm 'role' vào đây nếu bạn chưa có
        'google_id', // Thêm google_id để hỗ trợ OAuth
        'status', // Trạng thái: active / blocked
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'last_login' => 'datetime',
        ];
    }
    // Relationship với Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relationship với FavoriteDish
    public function favoriteDishes()
    {
        return $this->hasMany(FavoriteDish::class);
    }

    // Relationship với Product qua FavoriteDish
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorite_dishes', 'user_id', 'product_id')
                    ->withPivot('created_at')
                    ->orderBy('favorite_dishes.created_at', 'desc');
    }
}