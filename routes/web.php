<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
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
Route::get('/products/view/{id}', [ProductController::class, 'view'])->name('products.view');

Route::get('/products/{category}', [ProductController::class, 'show'])->name('products.category');



// Category products page
Route::get('/products/category/{category}', [ProductController::class, 'show'])->name('products.category');
// Check this line in your routes/web.php
Route::get('/productsPage/{slug}', [ProductController::class, 'showProductsBySlug'])->name('products.page');
// Individual product page
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');


Route::get('/about', [AboutController::class, 'index'])->name('about.index');

Route::get('/contact', function () {
    return view('contact');
});



Route::get('/search', function () {
    $query = request('query');
    // In a real application, you would search your database here
    return view('search', ['query' => $query]);
});



// Cart Routes with AJAX support
Route::group(['prefix' => 'cart'], function () {
    // Main cart page
    Route::get('/', [CartController::class, 'index'])->name('cart.index');

    // Add product to cart (AJAX)
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');

    // AJAX routes for dynamic updates
    Route::put('/update-quantity/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update.quantity');
    Route::delete('/remove-item/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove.item');
    Route::delete('/clear-cart', [CartController::class, 'clearCart'])->name('cart.clear.ajax');

    // Fallback routes for non-JavaScript users
    Route::put('/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Utility routes
    Route::get('/count', [CartController::class, 'getCount'])->name('cart.count');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('cart.checkout');
});

Route::group(['prefix' => 'checkout'], function () {
    // Checkout page
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');

    // Process checkout (AJAX)
    Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');

    // Success page
    Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
});




Route::get('/contact', [ContactController::class, 'showForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
