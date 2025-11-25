<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    /**
     * Display the about page content.
     */
    public function index()
    {
        $about = About::first();

        if (!$about) {
            // Create default about page if it doesn't exist
            $about = About::create([
                'title' => 'About Us',
                'introduction' => 'Welcome to our company',
                'our_story' => 'Our story begins...',
                'mission' => 'Our mission statement',
                'vision' => 'Our vision for the future',
                'values' => 'Our core values',
                'team_description' => 'Meet our amazing team',
                'team_members' => [],
                'images' => [],
                'video_url' => null,
                'address' => 'Your company address',
                'phone' => '+1234567890',
                'email' => 'info@company.com',
                'meta_title' => 'About Us - Company Name',
                'meta_description' => 'Learn more about our company',
                'meta_keywords' => 'about, company, story',
                'updated_by' => Auth::id(),
            ]);
        }

        return view('admin.about.index', compact('about'));
    }

    /**
     * Show the form for editing the about page.
     */
    public function edit()
    {
        $about = About::firstOrFail();
        return view('admin.about.edit', compact('about'));
    }

    /**
     * Update the about page content.
     */
    public function update(Request $request)
    {
        $about = About::firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'introduction' => 'required|string',
            'our_story' => 'required|string',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'values' => 'required|string',
            'team_description' => 'nullable|string',
            'team_member_name.*' => 'nullable|string|max:255',
            'team_member_position.*' => 'nullable|string|max:255',
            'team_member_bio.*' => 'nullable|string',
            'team_member_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_url' => 'nullable|url|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
        ]);

        // Handle team members
        $teamMembers = [];
        if ($request->has('team_member_name')) {
            foreach ($request->team_member_name as $index => $name) {
                if (!empty($name)) {
                    $teamMember = [
                        'name' => $name,
                        'position' => $request->team_member_position[$index] ?? '',
                        'bio' => $request->team_member_bio[$index] ?? '',
                    ];

                    // Handle team member image upload
                    if ($request->hasFile("team_member_image.{$index}")) {
                        $image = $request->file("team_member_image.{$index}");
                        $path = $image->store('team-members', 'public');
                        $teamMember['image'] = $path;
                    } elseif (isset($about->team_members[$index]['image'])) {
                        // Keep existing image if not uploading new one
                        $teamMember['image'] = $about->team_members[$index]['image'];
                    }

                    $teamMembers[] = $teamMember;
                }
            }
        }
        $validated['team_members'] = $teamMembers;

        // Handle main images upload
        $imagePaths = [];

        // Keep existing images if not removing them
        if ($request->has('keep_images')) {
            foreach ($request->keep_images as $imageIndex) {
                if (isset($about->images[$imageIndex])) {
                    $imagePaths[] = $about->images[$imageIndex];
                }
            }
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('about', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['images'] = $imagePaths;
        $validated['updated_by'] = Auth::id();

        // Set published_at if not set and publishing for the first time
        if (!$about->published_at && $request->has('publish')) {
            $validated['published_at'] = now();
        }

        $about->update($validated);

        return redirect()->route('admin.about.index')
            ->with('success', 'About page updated successfully.');
    }

    /**
     * Remove a team member.
     */
    public function removeTeamMember(Request $request, $index)
    {
        $about = About::firstOrFail();
        $teamMembers = $about->team_members;

        if (isset($teamMembers[$index])) {
            // Delete team member image if exists
            if (isset($teamMembers[$index]['image'])) {
                Storage::disk('public')->delete($teamMembers[$index]['image']);
            }

            // Remove the team member
            unset($teamMembers[$index]);
            $teamMembers = array_values($teamMembers); // Reindex array

            $about->update([
                'team_members' => $teamMembers,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('admin.about.edit')
                ->with('success', 'Team member removed successfully.');
        }

        return redirect()->route('admin.about.edit')
            ->with('error', 'Team member not found.');
    }

    /**
     * Remove an image from the about page.
     */
    public function removeImage($index)
    {
        $about = About::firstOrFail();

        if (isset($about->images[$index])) {
            // Delete the image file
            Storage::disk('public')->delete($about->images[$index]);

            // Remove from images array
            $images = $about->images;
            unset($images[$index]);
            $images = array_values($images); // Reindex array

            $about->update([
                'images' => $images,
                'updated_by' => Auth::id(),
            ]);

            return redirect()->route('admin.about.edit')
                ->with('success', 'Image removed successfully.');
        }

        return redirect()->route('admin.about.edit')
            ->with('error', 'Image not found.');
    }

    /**
     * Publish the about page.
     */
    public function publish()
    {
        $about = About::firstOrFail();

        $about->update([
            'published_at' => now(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.about.index')
            ->with('success', 'About page published successfully.');
    }

    /**
     * Unpublish the about page.
     */
    public function unpublish()
    {
        $about = About::firstOrFail();

        $about->update([
            'published_at' => null,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('admin.about.index')
            ->with('success', 'About page unpublished successfully.');
    }

    /**
     * Reset the about page to default.
     */
    public function reset()
    {
        $about = About::firstOrFail();

        // Delete all images
        if (!empty($about->images)) {
            foreach ($about->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        // Delete team member images
        if (!empty($about->team_members)) {
            foreach ($about->team_members as $member) {
                if (isset($member['image'])) {
                    Storage::disk('public')->delete($member['image']);
                }
            }
        }

        // Reset to default content
        $about->update([
            'title' => 'About Us',
            'introduction' => 'Welcome to our company',
            'our_story' => 'Our story begins...',
            'mission' => 'Our mission statement',
            'vision' => 'Our vision for the future',
            'values' => 'Our core values',
            'team_description' => 'Meet our amazing team',
            'team_members' => [],
            'images' => [],
            'video_url' => null,
            'address' => 'Your company address',
            'phone' => '+1234567890',
            'email' => 'info@company.com',
            'meta_title' => 'About Us - Company Name',
            'meta_description' => 'Learn more about our company',
            'meta_keywords' => 'about, company, story',
            'updated_by' => Auth::id(),
            'published_at' => null,
        ]);

        return redirect()->route('admin.about.index')
            ->with('success', 'About page reset to default successfully.');
    }

    /**
     * Preview the about page.
     */
    public function preview()
    {
        $about = About::firstOrFail();
        return view('admin.about.preview', compact('about'));
    }

    private function getYouTubeId($url)
    {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Extract Vimeo video ID from URL
     */
    private function getVimeoId($url)
    {
        preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|)(\d+)(?:|\/\?)/', $url, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Export about page data.
     */
    public function export()
    {
        $about = About::firstOrFail();

        $data = [
            'title' => $about->title,
            'introduction' => $about->introduction,
            'our_story' => $about->our_story,
            'mission' => $about->mission,
            'vision' => $about->vision,
            'values' => $about->values,
            'team_description' => $about->team_description,
            'team_members' => $about->team_members,
            'video_url' => $about->video_url,
            'address' => $about->address,
            'phone' => $about->phone,
            'email' => $about->email,
            'meta_title' => $about->meta_title,
            'meta_description' => $about->meta_description,
            'meta_keywords' => $about->meta_keywords,
            'last_updated' => $about->updated_at->format('Y-m-d H:i:s'),
        ];

        $filename = 'about-page-data-' . date('Y-m-d') . '.json';

        return response()->json($data, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Import about page data.
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:json|max:1024',
        ]);

        $about = About::firstOrFail();

        try {
            $importData = json_decode(file_get_contents($request->file('import_file')->getPathname()), true);

            $allowedFields = [
                'title',
                'introduction',
                'our_story',
                'mission',
                'vision',
                'values',
                'team_description',
                'team_members',
                'video_url',
                'address',
                'phone',
                'email',
                'meta_title',
                'meta_description',
                'meta_keywords'
            ];

            $updateData = [];
            foreach ($allowedFields as $field) {
                if (isset($importData[$field])) {
                    $updateData[$field] = $importData[$field];
                }
            }

            $updateData['updated_by'] = Auth::id();

            $about->update($updateData);

            return redirect()->route('admin.about.index')
                ->with('success', 'About page data imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.about.index')
                ->with('error', 'Failed to import data. Please check the file format.');
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('about/images', 'public');

            return response()->json([
                'location' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'Upload failed'], 500);
    }
}
