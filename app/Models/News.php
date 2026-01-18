<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'image', 'image_url',
        'author', 'is_featured', 'is_published', 'views'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'views' => 'integer',
    ];

    /**
     * Tự động tạo slug từ title
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
        
        static::updating(function ($news) {
            if ($news->isDirty('title') && empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    /**
     * Scope để lấy tin tức đã xuất bản
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope để lấy tin tức nổi bật
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope để lấy tin tức mới nhất
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Tăng số lượt xem
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Lấy URL của tin tức
     */
    public function getUrlAttribute()
    {
        return route('news.show', $this->slug);
    }

    /**
     * Lấy ảnh đại diện (ưu tiên image_url, fallback về image)
     */
    public function getFeaturedImageAttribute()
    {
        if ($this->image_url) {
            return $this->image_url;
        }
        
        if ($this->image) {
            return asset($this->image);
        }
        
        return null;
    }
}
