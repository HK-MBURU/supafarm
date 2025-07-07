<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    /**
     * Display the about page
     */
    public function index()
    {
        $about = About::first() ?? new About();
        
        return view('about.index', [
            'about' => $about,
        ]);
    }
    
    /**
     * Display the about page edit form
     */
    public function edit()
    {
        $about = About::first() ?? new About();
        
        return view('about.edit', [
            'about' => $about,
        ]);
    }
    
    /**
     * Update the about page content
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'introduction' => 'nullable|string',
            'our_story' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'values' => 'nullable|string',
            'team_description' => 'nullable|string',
            'team_members' => 'nullable|array',
            'team_members.*.name' => 'nullable|string',
            'team_members.*.position' => 'nullable|string',
            'team_members.*.bio' => 'nullable|string',
            'team_members.*.image' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);
        
        // Handle images upload
        $images = [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('about-images', 'public');
                $images[] = $path;
            }
        }
        
        // Combine with existing images if any
        $about = About::first();
        if ($about && !empty($about->images)) {
            $images = array_merge($about->images, $images);
        }
        
        // Handle existing images (if some were removed)
        if ($request->has('existing_images')) {
            $existingImages = $request->input('existing_images');
            if ($about && !empty($about->images)) {
                foreach ($about->images as $key => $image) {
                    if (!in_array($image, $existingImages)) {
                        Storage::disk('public')->delete($image);
                        unset($images[$key]);
                    }
                }
                $images = array_values($images); // Re-index array
            }
        } elseif ($about && !empty($about->images)) {
            // If no existing images were passed, all were removed
            foreach ($about->images as $image) {
                Storage::disk('public')->delete($image);
            }
            $images = [];
        }
        
        // Handle team members
        $teamMembers = $request->input('team_members', []);
        
        // Create or update about record
        About::updateOrCreate(
            ['id' => $about ? $about->id : null],
            [
                'title' => $validated['title'],
                'introduction' => $validated['introduction'],
                'our_story' => $validated['our_story'],
                'mission' => $validated['mission'],
                'vision' => $validated['vision'],
                'values' => $validated['values'],
                'team_description' => $validated['team_description'],
                'team_members' => $teamMembers,
                'images' => $images,
                'video_url' => $validated['video_url'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'meta_title' => $validated['meta_title'],
                'meta_description' => $validated['meta_description'],
                'meta_keywords' => $validated['meta_keywords'],
                'updated_by' => 'HK-MBURU', // Replace with auth()->user()->name when using authentication
                'published_at' => now(),
            ]
        );
        
        return redirect()->route('about.edit')->with('success', 'About page content has been updated successfully.');
    }
}