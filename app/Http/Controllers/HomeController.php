<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
   public function index()
    {
        return view('home');
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
