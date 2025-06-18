<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function show($category)
    {
        // In a real application, you would fetch products from the database
        $products = [];
        
        switch ($category) {
            case 'honey':
                $products = [
                    ['id' => 1, 'name' => 'Raw Honey', 'price' => 12.99, 'image' => 'honey.jpg'],
                    ['id' => 2, 'name' => 'Manuka Honey', 'price' => 24.99, 'image' => 'honey.jpg'],
                    ['id' => 3, 'name' => 'Wildflower Honey', 'price' => 14.99, 'image' => 'honey.jpg'],
                ];
                break;
            case 'eggs':
                $products = [
                    ['id' => 4, 'name' => 'Free-Range Eggs (Dozen)', 'price' => 5.99, 'image' => 'eggs.jpg'],
                    ['id' => 5, 'name' => 'Organic Eggs (Dozen)', 'price' => 7.99, 'image' => 'eggs.jpg'],
                    ['id' => 6, 'name' => 'Duck Eggs (Half Dozen)', 'price' => 8.99, 'image' => 'eggs.jpg'],
                ];
                break;
            case 'coffee':
                $products = [
                    ['id' => 7, 'name' => 'Dark Roast Coffee', 'price' => 14.99, 'image' => 'coffee.jpg'],
                    ['id' => 8, 'name' => 'Medium Roast Coffee', 'price' => 13.99, 'image' => 'coffee.jpg'],
                    ['id' => 9, 'name' => 'Light Roast Coffee', 'price' => 12.99, 'image' => 'coffee.jpg'],
                ];
                break;
            default:
                return redirect('/products');
        }
        
        return view('products.category', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
