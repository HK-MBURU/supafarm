<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = News::latest();

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->has('status') && in_array($request->status, ['published', 'draft'])) {
            $query->where('is_published', $request->status === 'published');
        }

        // Featured filter
        if ($request->has('featured') && $request->featured === 'true') {
            $query->featured();
        }

        $news = $query->paginate(12)->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'author' => 'nullable|string|max:255',
        ], [
            'title.required' => 'The news title is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'excerpt.max' => 'The excerpt may not be greater than 500 characters.',
            'content.required' => 'The news content is required.',
            'featured_image.image' => 'The featured image must be a valid image.',
            'featured_image.mimes' => 'The featured image must be a JPEG, PNG, JPG, GIF, or WEBP.',
            'featured_image.max' => 'The featured image may not be greater than 5MB.',
            'gallery_images.array' => 'Gallery images must be an array.',
            'gallery_images.max' => 'You can upload maximum 10 gallery images.',
            'gallery_images.*.image' => 'Each gallery image must be a valid image.',
            'gallery_images.*.mimes' => 'Each gallery image must be a JPEG, PNG, JPG, GIF, or WEBP.',
            'gallery_images.*.max' => 'Each gallery image may not be greater than 5MB.',
            'published_at.date' => 'Please enter a valid publication date.',
            'author.max' => 'The author name may not be greater than 255 characters.',
        ]);

        try {
            DB::beginTransaction();

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $validated['featured_image'] = $this->handleImageUpload($request->file('featured_image'), 'news/featured');
            }

            // Handle gallery images upload
            $galleryPaths = [];
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $image) {
                    $galleryPaths[] = $this->handleImageUpload($image, 'news/gallery');
                }
                $validated['gallery_images'] = $galleryPaths;
            }

            // Set default author if not provided
            if (empty($validated['author'])) {
                $validated['author'] = auth()->user()->name;
            }

            // Set published_at to now if publishing and no date provided
            if ($request->boolean('is_published') && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            }

            $validated['is_published'] = $request->boolean('is_published', false);
            $validated['is_featured'] = $request->boolean('is_featured', false);

            // Create the news
            News::create($validated);

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'News article created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create news article: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        // Increment views when showing
        $news->incrementViews();

        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
            'author' => 'nullable|string|max:255',
        ], [
            'title.required' => 'The news title is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'excerpt.max' => 'The excerpt may not be greater than 500 characters.',
            'content.required' => 'The news content is required.',
            'featured_image.image' => 'The featured image must be a valid image.',
            'featured_image.mimes' => 'The featured image must be a JPEG, PNG, JPG, GIF, or WEBP.',
            'featured_image.max' => 'The featured image may not be greater than 5MB.',
            'gallery_images.array' => 'Gallery images must be an array.',
            'gallery_images.max' => 'You can upload maximum 10 gallery images.',
            'gallery_images.*.image' => 'Each gallery image must be a valid image.',
            'gallery_images.*.mimes' => 'Each gallery image must be a JPEG, PNG, JPG, GIF, or WEBP.',
            'gallery_images.*.max' => 'Each gallery image may not be greater than 5MB.',
            'published_at.date' => 'Please enter a valid publication date.',
            'author.max' => 'The author name may not be greater than 255 characters.',
        ]);

        try {
            DB::beginTransaction();

            $oldFeaturedImage = $news->featured_image;
            $oldGalleryImages = $news->gallery_images ?? [];

            // Handle featured image upload
            if ($request->hasFile('featured_image')) {
                $validated['featured_image'] = $this->handleImageUpload($request->file('featured_image'), 'news/featured');
                
                // Delete old featured image
                if ($oldFeaturedImage && Storage::disk('public')->exists($oldFeaturedImage)) {
                    Storage::disk('public')->delete($oldFeaturedImage);
                }
            } else {
                // Keep the existing featured image
                $validated['featured_image'] = $oldFeaturedImage;
            }

            // Handle gallery images upload
            if ($request->hasFile('gallery_images')) {
                $galleryPaths = [];
                foreach ($request->file('gallery_images') as $image) {
                    $galleryPaths[] = $this->handleImageUpload($image, 'news/gallery');
                }
                $validated['gallery_images'] = $galleryPaths;

                // Delete old gallery images
                foreach ($oldGalleryImages as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            } else {
                // Keep the existing gallery images
                $validated['gallery_images'] = $oldGalleryImages;
            }

            // Set published_at to now if publishing and no date provided and wasn't published before
            if ($request->boolean('is_published') && empty($validated['published_at']) && !$news->is_published) {
                $validated['published_at'] = now();
            }

            $validated['is_published'] = $request->boolean('is_published', false);
            $validated['is_featured'] = $request->boolean('is_featured', false);

            // Update the news
            $news->update($validated);

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'News article updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update news article: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        try {
            DB::beginTransaction();

            // Delete featured image
            if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
                Storage::disk('public')->delete($news->featured_image);
            }

            // Delete gallery images
            if ($news->gallery_images && is_array($news->gallery_images)) {
                foreach ($news->gallery_images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            // Delete the news article
            $news->delete();

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'News article deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to delete news article: ' . $e->getMessage());
        }
    }

    /**
     * Toggle publish status
     */
    public function toggleStatus(News $news)
    {
        try {
            $news->update([
                'is_published' => !$news->is_published,
                'published_at' => !$news->is_published ? now() : $news->published_at
            ]);

            $status = $news->is_published ? 'published' : 'unpublished';

            return redirect()->back()
                ->with('success', "News article {$status} successfully.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update news status.');
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(News $news)
    {
        try {
            $news->update(['is_featured' => !$news->is_featured]);

            $status = $news->is_featured ? 'featured' : 'unfeatured';

            return redirect()->back()
                ->with('success', "News article {$status} successfully.");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update featured status.');
        }
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload($image, $directory)
    {
        // Ensure directory exists
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $filename = Str::random(20) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $filePath = $image->storeAs($directory, $filename, 'public');

        // Optimize image
        try {
            $image = $this->imageManager->read(storage_path('app/public/' . $filePath));
            
            // Resize if too large (max width 1200px)
            if ($image->width() > 1200) {
                $image->scaleDown(1200);
            }
            
            // Save with optimized quality
            $image->save(storage_path('app/public/' . $filePath), quality: 85);
        } catch (\Exception $e) {
            // If image optimization fails, continue with original
        }

        return $filePath;
    }

    /**
     * Remove gallery image
     */
    public function removeGalleryImage(News $news, $imageIndex)
    {
        try {
            $galleryImages = $news->gallery_images ?? [];

            if (isset($galleryImages[$imageIndex])) {
                $imageToRemove = $galleryImages[$imageIndex];

                // Remove from array
                unset($galleryImages[$imageIndex]);
                $galleryImages = array_values($galleryImages); // Reindex array

                // Delete file from storage
                if (Storage::disk('public')->exists($imageToRemove)) {
                    Storage::disk('public')->delete($imageToRemove);
                }

                // Update news
                $news->update(['gallery_images' => $galleryImages]);

                return response()->json([
                    'success' => true,
                    'message' => 'Gallery image removed successfully.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Image not found.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove gallery image.'
            ], 500);
        }
    }
}