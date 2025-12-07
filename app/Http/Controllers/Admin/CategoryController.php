<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        Log::info('Creating category with slug:', [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'is_active' => $validated['is_active']
        ]);

        try {
            Category::create($validated);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            Log::error('Category creation failed: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'Failed to create category. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('products')->findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $validated['image'] = $imagePath;
        }

        // Update slug only if name changed
        if ($request->name !== $category->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle checkbox
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category. It has associated products.');
        }

        // Delete image if exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(string $id)
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category status updated successfully.');
    }
}
