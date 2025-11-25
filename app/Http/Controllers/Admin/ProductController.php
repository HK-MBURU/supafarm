<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string',
            'features' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // Handle multiple image uploads
        $imagePaths = [];
        $originalFilenames = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
                $originalFilenames[] = $image->getClientOriginalName();
            }
            $validated['image'] = $imagePaths;
            $validated['original_filename'] = $originalFilenames;
        }

        // Handle boolean fields
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        // Generate slug if needed
        // $validated['slug'] = Str::slug($validated['name']);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'reviews'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'required|string',
            'features' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($product->image && is_array($product->image)) {
                foreach ($product->image as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $imagePaths = [];
            $originalFilenames = [];

            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
                $originalFilenames[] = $image->getClientOriginalName();
            }

            $validated['image'] = $imagePaths;
            $validated['original_filename'] = $originalFilenames;
        } elseif ($request->has('remove_images')) {
            // Handle image removal
            $remainingImages = [];
            $remainingFilenames = [];

            if ($product->image && is_array($product->image)) {
                foreach ($product->image as $index => $imagePath) {
                    if (!in_array($index, $request->remove_images)) {
                        $remainingImages[] = $imagePath;
                        $remainingFilenames[] = $product->original_filename[$index] ?? null;
                    } else {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }

            $validated['image'] = $remainingImages;
            $validated['original_filename'] = $remainingFilenames;
        }

        // Handle boolean fields
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Check if product has related records
        if ($product->orderItems()->count() > 0 || $product->reviews()->count() > 0 || $product->cartItems()->count() > 0) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Cannot delete product. It has associated orders, reviews, or cart items.');
        }

        // Delete images
        if ($product->image && is_array($product->image)) {
            foreach ($product->image as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Display featured products.
     */
    public function featured()
    {
        $products = Product::with('category')
            ->where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->paginate(10);

        return view('admin.products.featured', compact('products'));
    }

    /**
     * Toggle product featured status.
     */
    public function toggleFeatured(string $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_featured' => !$product->is_featured]);

        $message = $product->is_featured ? 'Product marked as featured.' : 'Product removed from featured.';

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Toggle product active status.
     */
    public function toggleStatus(string $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);

        $message = $product->is_active ? 'Product activated.' : 'Product deactivated.';

        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Update stock quantity.
     */
    public function updateStock(Request $request, string $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $product = Product::findOrFail($id);
        $product->update(['stock' => $request->stock]);

        return redirect()->back()
            ->with('success', 'Stock quantity updated successfully.');
    }
}
