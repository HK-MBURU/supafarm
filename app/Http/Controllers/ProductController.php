<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index($categorySlug = null)
    {
        if ($categorySlug) {
            // Find the category
            $category = Category::where('slug', $categorySlug)->firstOrFail();

            // Get products for this category
            $products = Product::with('category')
                ->where('category_id', $category->id)
                ->where('is_active', true)
                ->paginate(12);
        } else {
            // Show all products
            $category = (object) ['name' => 'All Products'];
            $products = Product::with('category')
                ->where('is_active', true)
                ->paginate(12);
        }

        return view('products.index', compact('products', 'category'));
    }

    public function show($category)
    {

        // Find the category or throw 404
        $category = Category::findOrFail($category);

        // Get active products in this category with pagination
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc') // Featured products first
            ->orderBy('created_at', 'desc')  // Then newest products
            ->paginate(12); // Show 12 products per page

        // Count total products in this category (for display)
        $totalProducts = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->count();



        return view('products.index', [
            'category' => $category,
            'products' => $products,
            'totalProducts' => $totalProducts,

        ]);
    }
    public function showProductsBySlug($slug)
    {
        Log::info('Reached heree: ' . $slug);
        // Find the category or throw 404
        try {
            $category = Category::where('slug', $slug)->firstOrFail();
        } catch (\Exception $e) {
            Log::error('Category not found: ' . $e->getMessage());
            abort(404);
        }

        // Get active products in this category with pagination
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('is_featured', 'desc') // Featured products first
            ->orderBy('created_at', 'desc')  // Then newest products
            ->paginate(12); // Show 12 products per page

        // Count total products in this category (for display)
        $totalProducts = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->count();



        return view('products.index', [
            'category' => $category,
            'products' => $products,
            'totalProducts' => $totalProducts,

        ]);
    }

    public function view($id)
    {
        // Find the product by ID or throw a 404 error if not found
        $product = Product::findOrFail($id);

        // Load the product's category relationship
        $product->load('category');

        // Get related products (products in the same category, excluding the current product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // You can load reviews if you have a reviews relationship
        // $product->load('reviews');

        // Return the view with the product and related data
        return view('products.view', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
