<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
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
}
