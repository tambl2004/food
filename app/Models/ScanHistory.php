<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanHistory extends Model
{
    use HasFactory;

    protected $table = 'scan_history';

    protected $fillable = [
        'user_id',
        'image_path',
        'detected_ingredients',
    ];

    protected $casts = [
        'detected_ingredients' => 'array',
    ];

    /**
     * Relationship với User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
