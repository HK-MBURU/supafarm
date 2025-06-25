<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active',
    ];
    protected $casts = [
        'image' => 'array',
        'original_filename' => 'array',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }

        // If image is a JSON string that got decoded to an array
        if (is_array($this->image)) {
            // Return the first image in the array
            return asset('storage/' . $this->image[0]);
        }

        // Regular string case
        return asset('storage/' . $this->image);
    }

    // Add this method to get all images if needed
    public function getImageUrlsAttribute()
    {
        if (empty($this->image)) {
            return [];
        }

        if (is_array($this->image)) {
            // Map all images to their full URLs
            return array_map(function ($img) {
                return asset('storage/' . $img);
            }, $this->image);
        }

        // Single image case
        return [asset('storage/' . $this->image)];
    }
}
