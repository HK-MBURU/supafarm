<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery_images',
        'is_published',
        'is_featured',
        'published_at',
        'views',
        'author',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'gallery_images' => 'array',
    ];

    protected $appends = [
        'featured_image_url',
        'gallery_image_urls',
        'read_time',
        'published_date',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            
            // Ensure unique slug
            $originalSlug = $news->slug;
            $counter = 1;
            while (static::where('slug', $news->slug)->exists()) {
                $news->slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        });

        static::updating(function ($news) {
            // Update slug if title changed
            if ($news->isDirty('title') && empty($news->getOriginal('slug'))) {
                $news->slug = Str::slug($news->title);
                
                // Ensure unique slug
                $originalSlug = $news->slug;
                $counter = 1;
                while (static::where('slug', $news->slug)->where('id', '!=', $news->id)->exists()) {
                    $news->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    /**
     * Get featured image URL
     */
    protected function featuredImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->featured_image && Storage::disk('public')->exists($this->featured_image)) {
                    return asset('storage/' . $this->featured_image);
                }
                return asset('images/default-news.jpg');
            }
        );
    }

    /**
     * Get gallery image URLs
     */
    protected function galleryImageUrls(): Attribute
    {
        return Attribute::make(
            get: function () {
                $urls = [];
                
                if ($this->gallery_images && is_array($this->gallery_images)) {
                    foreach ($this->gallery_images as $image) {
                        if (Storage::disk('public')->exists($image)) {
                            $urls[] = asset('storage/' . $image);
                        }
                    }
                }
                
                return $urls;
            }
        );
    }

    /**
     * Calculate read time in minutes
     */
    protected function readTime(): Attribute
    {
        return Attribute::make(
            get: function () {
                $wordCount = str_word_count(strip_tags($this->content));
                $minutes = ceil($wordCount / 200); // Average reading speed: 200 words per minute
                return max(1, $minutes); // Minimum 1 minute
            }
        );
    }

    /**
     * Get formatted published date
     */
    protected function publishedDate(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->published_at) {
                    return $this->published_at->format('M j, Y');
                }
                return $this->created_at->format('M j, Y');
            }
        );
    }

    /**
     * Scope for published news
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where(function ($query) {
                        $query->whereNull('published_at')
                              ->orWhere('published_at', '<=', now());
                    });
    }

    /**
     * Scope for featured news
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for recent news
     */
    public function scopeRecent($query, $limit = 5)
    {
        return $query->published()
                    ->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit);
    }

    /**
     * Scope for popular news (by views)
     */
    public function scopePopular($query, $limit = 5)
    {
        return $query->published()
                    ->orderBy('views', 'desc')
                    ->orderBy('published_at', 'desc')
                    ->limit($limit);
    }

    /**
     * Scope for searching news
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get the route key for the model (for pretty URLs)
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Check if news is published
     */
    public function isPublished(): bool
    {
        return $this->is_published && 
               ($this->published_at === null || $this->published_at <= now());
    }
}