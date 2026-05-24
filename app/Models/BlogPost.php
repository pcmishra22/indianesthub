<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'category', 'author_id', 'status', 'published_at', 'views_count',
        'meta_title', 'meta_description'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * Route model binding — use slug.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Auto-generate slug on create/update if missing.
     */
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($post) {
            if (empty($post->slug) && !empty($post->title)) {
                $post->slug = Str::slug($post->title);
            }
        });
        static::updating(function ($post) {
            if (empty($post->slug) && !empty($post->title)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function scopePublished($query)
    {
        // Existing data in this project may have `status=published` but `published_at` unset.
        // To avoid hiding all posts, treat `status=published` as the primary published flag.
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

}
