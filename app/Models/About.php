<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'introduction',
        'our_story',
        'mission',
        'vision',
        'values',
        'team_description',
        'team_members',
        'images',
        'video_url',
        'address',
        'phone',
        'email',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'updated_by',
        'published_at',
    ];

    protected $casts = [
        'team_members' => 'array',
        'images' => 'array',
        'published_at' => 'datetime',
    ];
    
    // Method to get formatted image URLs
    public function getImageUrlsAttribute()
    {
        if (empty($this->images)) {
            return [];
        }
        
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }
    
    // Method to get the main image URL
    public function getMainImageUrlAttribute()
    {
        if (empty($this->images)) {
            return asset('images/default-about.jpg');
        }
        
        return asset('storage/' . $this->images[0]);
    }
}