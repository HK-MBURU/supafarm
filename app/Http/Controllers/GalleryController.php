<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display gallery page
     */
    public function index()
    {
        $images = Media::active()
                      ->images()
                      ->ordered()
                      ->get();

        $videos = Media::active()
                      ->whereIn('type', ['video', 'youtube', 'tiktok'])
                      ->ordered()
                      ->get();

        return view('gallery.index', compact('images', 'videos'));
    }

    /**
     * Get images for gallery section (for partial)
     */
    public function getGalleryImages()
    {
        $images = Media::active()
                      ->images()
                      ->ordered()
                      ->take(12) // Limit for scrolling gallery
                      ->get();

        return $images;
    }

    /**
     * Get all media for gallery page
     */
    public function getAllMedia()
    {
        $media = Media::active()
                     ->ordered()
                     ->paginate(24); // 24 items per page for gallery

        return view('gallery.all', compact('media'));
    }

    /**
     * View single media item
     */
    public function show($id)
    {
        $media = Media::findOrFail($id);

        // Get related media
        $related = Media::active()
                       ->where('id', '!=', $media->id)
                       ->where('type', $media->type)
                       ->ordered()
                       ->limit(6)
                       ->get();

        return view('gallery.show', compact('media', 'related'));
    }
}
