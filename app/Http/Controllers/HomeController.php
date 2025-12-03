<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
     public function index()
    {
        $categories = Category::where('is_active', 1)->get();

        // Fetch the about section data
        $about = About::where('published_at', '<=', now())
            ->latest('published_at')
            ->first();

        return view('home', compact('categories', 'about'));
    }



    public function products()
    {
        return view('products');
    }

    public function productCategory($category)
    {
        return view('product-category', ['category' => $category]);
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function cart()
    {
        return view('cart');
    }
}
