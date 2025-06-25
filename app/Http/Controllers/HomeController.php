<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
   public function index()
    {
        $categories=Category::where('is_active', 1)->get();

        return view('home',compact('categories'));
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
