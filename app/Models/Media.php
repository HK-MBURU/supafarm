<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'file_path',
        'video_url',
        'video_id',
        'thumbnail_path',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $appends = [
        'display_type',
        'thumbnail_url',
        'file_url',
        'embed_url',
        'file_size',
        'file_extension',
    ];

    /**
     * Get the display type
     */
    protected function displayType(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->type) {
                'image' => 'Image',
                'video' => 'Video File',
                'youtube' => 'YouTube Video',
                'tiktok' => 'TikTok Video',
                default => 'Unknown',
            }
        );
    }

    /**
     * Get the thumbnail URL
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->thumbnail_path) {
                    if (filter_var($this->thumbnail_path, FILTER_VALIDATE_URL)) {
                        return $this->thumbnail_path;
                    }
                    return Storage::disk('public')->exists($this->thumbnail_path)
                        ? asset('storage/' . $this->thumbnail_path)
                        : asset('images/default-thumbnail.jpg');
                }

                if ($this->type === 'image' && $this->file_path) {
                    return $this->file_url;
                }

                return asset('images/default-thumbnail.jpg');
            }
        );
    }

    /**
     * Get the file URL
     */
    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (in_array($this->type, ['youtube', 'tiktok'])) {
                    return $this->video_url;
                }

                if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
                    return asset('storage/' . $this->file_path);
                }

                return null;
            }
        );
    }

    /**
     * Get embed URL for YouTube or TikTok
     */
    protected function embedUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->type === 'youtube' && $this->video_id) {
                    return "https://www.youtube.com/embed/{$this->video_id}";
                }

                if ($this->type === 'tiktok' && $this->video_id) {
                    return "https://www.tiktok.com/embed/v2/{$this->video_id}";
                }

                return null;
            }
        );
    }

    /**
     * Get file size in human readable format
     */
    protected function fileSize(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
                    $bytes = Storage::disk('public')->size($this->file_path);
                    return $this->formatBytes($bytes);
                }
                return null;
            }
        );
    }

    /**
     * Get file extension
     */
    protected function fileExtension(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->file_path) {
                    return pathinfo($this->file_path, PATHINFO_EXTENSION);
                }
                return null;
            }
        );
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($media) {
            // Delete associated files
            if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
            if ($media->thumbnail_path && !filter_var($media->thumbnail_path, FILTER_VALIDATE_URL)
                && Storage::disk('public')->exists($media->thumbnail_path)) {
                Storage::disk('public')->delete($media->thumbnail_path);
            }
        });
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeYoutube($query)
    {
        return $query->where('type', 'youtube');
    }

    public function scopeTiktok($query)
    {
        return $query->where('type', 'tiktok');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
