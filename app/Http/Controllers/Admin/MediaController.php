<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\DB;

class MediaController extends Controller
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
        $query = Media::ordered();

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Type filter
        if ($request->has('type') && $request->type) {
            $query->ofType($request->type);
        }

        // Status filter
        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('is_active', $request->status === 'active');
        }

        $media = $query->paginate(12)->withQueryString();

        return view('admin.media.index', compact('media'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // First, validate basic fields
        $basicValidation = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video,youtube,tiktok',
            'is_active' => 'boolean',
        ], [
            'title.required' => 'The media title is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'type.required' => 'Please select a media type.',
            'type.in' => 'Please select a valid media type.',
        ]);

        // Conditional validation based on media type
        $mediaType = $request->type;

        if ($mediaType === 'image' || $mediaType === 'video') {
            $fileValidation = $request->validate([
                'files' => 'required|array|max:20',
                'files.*' => 'file|mimes:' . ($mediaType === 'image' ? 'jpeg,png,jpg,gif,webp,svg' : 'mp4,mov,avi,wmv') . '|max:10240',
            ], [
                'files.required' => 'Please upload at least one file for this media type.',
                'files.array' => 'Files must be uploaded as an array.',
                'files.max' => 'You can upload maximum 20 files at once.',
                'files.*.file' => 'Each file must be a valid file.',
                'files.*.mimes' => 'Invalid file type. Allowed types: ' . ($mediaType === 'image' ? 'JPEG, PNG, JPG, GIF, WEBP, SVG' : 'MP4, MOV, AVI, WMV'),
                'files.*.max' => 'File size must be less than 10MB.',
            ]);
        } else {
            $linkValidation = $request->validate([
                'links' => 'required|array|max:20',
                'links.*' => 'required|url',
            ], [
                'links.required' => 'Please add at least one video link.',
                'links.array' => 'Links must be provided as an array.',
                'links.max' => 'You can add maximum 20 links at once.',
                'links.*.required' => 'Each link field is required.',
                'links.*.url' => 'Please enter a valid URL for each link.',
            ]);
        }

        try {
            DB::beginTransaction();

            $createdCount = 0;
            $isActive = $request->boolean('is_active', true);
            $baseOrder = Media::max('order') ?? 0;

            // Handle multiple file uploads (images or videos)
            if ($request->hasFile('files') && in_array($mediaType, ['image', 'video'])) {
                $files = $request->file('files');

                // Additional validation for file types based on selected media type
                foreach ($files as $index => $file) {
                    if ($mediaType === 'image' && !str_starts_with($file->getMimeType(), 'image/')) {
                        throw new \Exception("File {$file->getClientOriginalName()} is not a valid image file.");
                    }

                    if ($mediaType === 'video' && !str_starts_with($file->getMimeType(), 'video/')) {
                        throw new \Exception("File {$file->getClientOriginalName()} is not a valid video file.");
                    }

                    $fileData = $this->handleFileUpload($file, $mediaType);

                    Media::create([
                        'title' => $request->title . ($index > 0 ? ' (' . ($index + 1) . ')' : ''),
                        'description' => $request->description,
                        'type' => $mediaType,
                        'file_path' => $fileData['file_path'],
                        'thumbnail_path' => $fileData['thumbnail_path'],
                        'is_active' => $isActive,
                        'order' => ++$baseOrder,
                    ]);

                    $createdCount++;
                }
            }

            // Handle multiple video links (YouTube or TikTok)
            if ($request->has('links') && in_array($mediaType, ['youtube', 'tiktok'])) {
                $links = array_filter($request->links, fn($link) => !empty(trim($link)));

                if (empty($links)) {
                    throw new \Exception('Please provide at least one valid video link.');
                }

                foreach ($links as $index => $link) {
                    $link = trim($link);
                    $videoData = $this->handleVideoLink($link, $mediaType);

                    if (!$videoData) {
                        throw new \Exception("Invalid {$mediaType} link: {$link}");
                    }

                    Media::create([
                        'title' => $request->title . ($index > 0 ? ' (' . ($index + 1) . ')' : ''),
                        'description' => $request->description,
                        'type' => $mediaType,
                        'video_url' => $videoData['video_url'],
                        'video_id' => $videoData['video_id'],
                        'thumbnail_path' => $videoData['thumbnail_path'],
                        'is_active' => $isActive,
                        'order' => ++$baseOrder,
                    ]);

                    $createdCount++;
                }
            }

            DB::commit();

            $message = $createdCount > 1
                ? "{$createdCount} media items created successfully."
                : 'Media item created successfully.';

            return redirect()->route('admin.media.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create media items: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        return view('admin.media.show', compact('media'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        return view('admin.media.edit', compact('media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video,youtube,tiktok',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg,mp4,mov,avi,wmv|max:10240',
            'video_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        try {
            $oldType = $media->type;
            $oldFilePath = $media->file_path;
            $oldThumbnailPath = $media->thumbnail_path;

            // Handle file upload if a new file is provided
            if ($request->hasFile('file') && in_array($validated['type'], ['image', 'video'])) {
                $fileData = $this->handleFileUpload($request->file('file'), $validated['type']);
                $validated['file_path'] = $fileData['file_path'];
                $validated['thumbnail_path'] = $fileData['thumbnail_path'];
                $validated['video_url'] = null;
                $validated['video_id'] = null;

                // Clean up old files
                $this->cleanupOldFiles($oldFilePath, $oldThumbnailPath);
            }

            // Handle video link
            if ($request->filled('video_url') && in_array($validated['type'], ['youtube', 'tiktok'])) {
                $videoData = $this->handleVideoLink($validated['video_url'], $validated['type']);

                if ($videoData) {
                    $validated['video_url'] = $videoData['video_url'];
                    $validated['video_id'] = $videoData['video_id'];
                    $validated['thumbnail_path'] = $videoData['thumbnail_path'];
                    $validated['file_path'] = null;

                    // Clean up old files if switching from file to video link
                    if (!in_array($oldType, ['youtube', 'tiktok'])) {
                        $this->cleanupOldFiles($oldFilePath, $oldThumbnailPath);
                    }
                }
            }

            $validated['is_active'] = $request->boolean('is_active', true);

            $media->update($validated);

            return redirect()->route('admin.media.index')
                ->with('success', 'Media item updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update media item: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $media)
    {
        try {
            $media->delete();

            return redirect()->route('admin.media.index')
                ->with('success', 'Media item deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete media item: ' . $e->getMessage());
        }
    }

    /**
     * Update media order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
        ]);

        try {
            foreach ($request->order as $index => $id) {
                Media::where('id', $id)->update(['order' => $index + 1]);
            }

            return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update order.'], 500);
        }
    }

    /**
     * Toggle media status
     */
    public function toggleStatus(Media $media)
    {
        try {
            $media->update(['is_active' => !$media->is_active]);

            $status = $media->is_active ? 'activated' : 'deactivated';

            return redirect()->back()
                ->with('success', "Media item {$status} successfully.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update media status.');
        }
    }

    /**
     * Handle file upload
     */
    private function handleFileUpload($file, $type)
    {
        $filename = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('media', $filename, 'public');

        $data = ['file_path' => $filePath];

        // Generate thumbnail for images and videos
        if ($type === 'image') {
            $data['thumbnail_path'] = $this->generateImageThumbnail($filePath);
        } elseif ($type === 'video') {
            $data['thumbnail_path'] = $this->generateVideoThumbnail($filePath);
        }

        return $data;
    }

    /**
     * Handle video link (YouTube or TikTok)
     */
    private function handleVideoLink($url, $type)
    {
        if ($type === 'youtube') {
            $videoId = $this->extractYouTubeId($url);
            if ($videoId) {
                return [
                    'video_url' => $url,
                    'video_id' => $videoId,
                    'thumbnail_path' => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg"
                ];
            }
        } elseif ($type === 'tiktok') {
            $videoId = $this->extractTikTokId($url);
            if ($videoId) {
                return [
                    'video_url' => $url,
                    'video_id' => $videoId,
                    'thumbnail_path' => $this->getTikTokThumbnail()
                ];
            }
        }

        return null;
    }

    /**
     * Generate image thumbnail
     */
    private function generateImageThumbnail($filePath)
    {
        try {
            $thumbnailPath = 'media/thumbnails/' . pathinfo($filePath, PATHINFO_FILENAME) . '_thumb.jpg';

            // Ensure thumbnails directory exists
            if (!Storage::disk('public')->exists('media/thumbnails')) {
                Storage::disk('public')->makeDirectory('media/thumbnails');
            }

            $image = $this->imageManager->read(storage_path('app/public/' . $filePath));
            $image->cover(300, 200);
            $image->save(storage_path('app/public/' . $thumbnailPath), quality: 80);

            return $thumbnailPath;
        } catch (\Exception $e) {
            return $filePath;
        }
    }

    /**
     * Generate video thumbnail
     */
    private function generateVideoThumbnail($filePath)
    {
        $placeholderPath = 'media/thumbnails/video-placeholder.jpg';

        if (!Storage::disk('public')->exists($placeholderPath)) {
            try {
                // Ensure thumbnails directory exists
                if (!Storage::disk('public')->exists('media/thumbnails')) {
                    Storage::disk('public')->makeDirectory('media/thumbnails');
                }

                $image = $this->imageManager->create(300, 200)->fill('#e0e0e0');
                $image->save(storage_path('app/public/' . $placeholderPath));
            } catch (\Exception $e) {
                // Silently fail
            }
        }

        return $placeholderPath;
    }

    /**
     * Get TikTok placeholder thumbnail
     */
    private function getTikTokThumbnail()
    {
        $placeholderPath = 'media/thumbnails/tiktok-placeholder.jpg';

        if (!Storage::disk('public')->exists($placeholderPath)) {
            try {
                // Ensure thumbnails directory exists
                if (!Storage::disk('public')->exists('media/thumbnails')) {
                    Storage::disk('public')->makeDirectory('media/thumbnails');
                }

                $image = $this->imageManager->create(300, 200)->fill('#000000');
                $image->save(storage_path('app/public/' . $placeholderPath));
            } catch (\Exception $e) {
                // Silently fail
            }
        }

        return $placeholderPath;
    }

    /**
     * Extract YouTube ID from URL
     */
    private function extractYouTubeId($url)
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract TikTok ID from URL
     */
    private function extractTikTokId($url)
    {
        preg_match('/tiktok\.com\/.*\/video\/(\d+)/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Clean up old files
     */
    private function cleanupOldFiles($filePath, $thumbnailPath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        if (
            $thumbnailPath && !filter_var($thumbnailPath, FILTER_VALIDATE_URL)
            && Storage::disk('public')->exists($thumbnailPath)
        ) {
            Storage::disk('public')->delete($thumbnailPath);
        }
    }
}
