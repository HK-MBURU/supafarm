<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



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
    $products = collect(); // Empty collection by default

    if ($query) {
        $products = \App\Models\Product::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->with('category')
            ->get();
    }

    return view('search', [
        'query' => $query,
        'products' => $products
    ]);
})->name('search');



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


// admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    // Dashboard
    Route::get('/supafarm-admin', [AdminController::class, 'index'])->name('supafarm');

    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::patch('categories/{category}/toggle-status', [\App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');

    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::get('products/featured', [\App\Http\Controllers\Admin\ProductController::class, 'featured'])->name('products.featured');
    Route::get('products/featured', [\App\Http\Controllers\Admin\ProductController::class, 'featured'])->name('products.featured');
    Route::patch('products/{product}/toggle-featured', [\App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])->name('products.toggleFeatured');
    Route::patch('products/{product}/toggle-status', [\App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
    Route::patch('products/{product}/stock', [\App\Http\Controllers\Admin\ProductController::class, 'updateStock'])->name('products.updateStock');

    // Orders
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('orders/pending', [\App\Http\Controllers\Admin\OrderController::class, 'pending'])->name('orders.pending');
    Route::get('orders/delivery', [\App\Http\Controllers\Admin\OrderController::class, 'delivery'])->name('orders.delivery');
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('orders/pending', [\App\Http\Controllers\Admin\OrderController::class, 'pending'])->name('orders.pending');
    Route::get('orders/delivery', [\App\Http\Controllers\Admin\OrderController::class, 'delivery'])->name('orders.delivery');
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('orders/{order}/delivery-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateDeliveryStatus'])->name('orders.updateDeliveryStatus');
    Route::patch('orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
    Route::patch('orders/{order}/assign-delivery', [\App\Http\Controllers\Admin\OrderController::class, 'assignDelivery'])->name('orders.assignDelivery');
    Route::patch('orders/{order}/mark-delivered', [\App\Http\Controllers\Admin\OrderController::class, 'markAsDelivered'])->name('orders.markAsDelivered');
    Route::patch('orders/{order}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/statistics', [\App\Http\Controllers\Admin\OrderController::class, 'statistics'])->name('orders.statistics');

    // Content Management
    Route::get('contacts', [\App\Http\Controllers\Admin\ContentController::class, 'contacts'])->name('contacts');
    Route::put('contacts', [\App\Http\Controllers\Admin\ContentController::class, 'updateContacts'])->name('contacts.update');

    // User Profile
    Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // About Page Management
    Route::prefix('about')->name('about.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AboutController::class, 'index'])->name('index');
        Route::get('/edit', [\App\Http\Controllers\Admin\AboutController::class, 'edit'])->name('edit');
        Route::put('/update', [\App\Http\Controllers\Admin\AboutController::class, 'update'])->name('update');
        Route::post('/publish', [\App\Http\Controllers\Admin\AboutController::class, 'publish'])->name('publish');
        Route::post('/unpublish', [\App\Http\Controllers\Admin\AboutController::class, 'unpublish'])->name('unpublish');
        Route::delete('/team-member/{index}', [\App\Http\Controllers\Admin\AboutController::class, 'removeTeamMember'])->name('removeTeamMember');
        Route::delete('/image/{index}', [\App\Http\Controllers\Admin\AboutController::class, 'removeImage'])->name('removeImage');
        Route::post('/reset', [\App\Http\Controllers\Admin\AboutController::class, 'reset'])->name('reset');
        Route::get('/preview', [\App\Http\Controllers\Admin\AboutController::class, 'preview'])->name('preview');
        Route::get('/export', [\App\Http\Controllers\Admin\AboutController::class, 'export'])->name('export');
        Route::post('/import', [\App\Http\Controllers\Admin\AboutController::class, 'import'])->name('import');
        Route::post('/upload-image', [\App\Http\Controllers\Admin\AboutController::class, 'uploadImage'])->name('uploadImage');
    });
});
