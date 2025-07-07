<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/', [HomeController::class, 'index']);

Route::get('/products', [ProductController::class, 'index']);

Route::get('/products/{category}', [ProductController::class, 'show'])->name('products.category');

// Category products page
Route::get('/products/category/{category}', [ProductController::class, 'show'])->name('products.category');
// Check this line in your routes/web.php
Route::get('/productsPage/{slug}', [ProductController::class, 'showProductsBySlug'])->name('products.page');
// Individual product page
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');


Route::get('/about',[AboutController::class, 'index'])->name('about.index');

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/cart', function () {
    return view('cart');
});

Route::get('/search', function () {
    $query = request('query');
    // In a real application, you would search your database here
    return view('search', ['query' => $query]);
});

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');



Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
