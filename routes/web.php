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
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();

// routes for lazy-loaded sections
Route::get('/home/section/{section}', [HomeController::class, 'loadSection'])->name('home.section');

Route::get('/', [HomeController::class, 'index']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
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

// News routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// Gallery routes
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/all', [GalleryController::class, 'getAllMedia'])->name('gallery.all');
Route::get('/gallery/{id}', [GalleryController::class, 'show'])->name('gallery.show');




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
    Route::patch('orders/{order}/delivery-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateDeliveryStatus'])->name('orders.updateDeliveryStatus');
    Route::patch('orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.updatePaymentStatus');
    Route::patch('orders/{order}/assign-delivery', [\App\Http\Controllers\Admin\OrderController::class, 'assignDelivery'])->name('orders.assignDelivery');
    Route::patch('orders/{order}/mark-delivered', [\App\Http\Controllers\Admin\OrderController::class, 'markAsDelivered'])->name('orders.markAsDelivered');
    Route::patch('orders/{order}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('orders/statistics', [\App\Http\Controllers\Admin\OrderController::class, 'statistics'])->name('orders.statistics');

    // Bulk Actions for Orders
    Route::post('orders/bulk/confirm', [\App\Http\Controllers\Admin\OrderController::class, 'bulkConfirm'])->name('orders.bulk.confirm');
    Route::post('orders/bulk/processing', [\App\Http\Controllers\Admin\OrderController::class, 'bulkProcessing'])->name('orders.bulk.processing');
    Route::post('orders/bulk/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'bulkCancel'])->name('orders.bulk.cancel');



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

    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ContactController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ContactController::class, 'store'])->name('store');
        Route::get('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('show');
        Route::get('/{contact}/edit', [\App\Http\Controllers\Admin\ContactController::class, 'edit'])->name('edit');
        Route::put('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'update'])->name('update');
        Route::delete('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('destroy');

        // Bulk Actions
        Route::post('/bulk-delete', [\App\Http\Controllers\Admin\ContactController::class, 'bulkDestroy'])->name('bulk.destroy');
        Route::post('/mark-read', [\App\Http\Controllers\Admin\ContactController::class, 'markAsRead'])->name('mark.read');
        Route::post('/mark-unread', [\App\Http\Controllers\Admin\ContactController::class, 'markAsUnread'])->name('mark.unread');

        // Export
        Route::get('/export', [\App\Http\Controllers\Admin\ContactController::class, 'export'])->name('export');
    });

    // Media Routes
   Route::resource('media', \App\Http\Controllers\Admin\MediaController::class)->parameters([
        'media' => 'media' // This tells Laravel to use 'media' instead of 'medium'
    ]);
    Route::post('media/order', [\App\Http\Controllers\Admin\MediaController::class, 'updateOrder'])->name('media.updateOrder');
    Route::patch('media/{media}/toggle-status', [\App\Http\Controllers\Admin\MediaController::class, 'toggleStatus'])->name('media.toggle-status');

    // News Routes
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    Route::patch('news/{news}/toggle-status', [\App\Http\Controllers\Admin\NewsController::class, 'toggleStatus'])->name('news.toggle-status');
    Route::patch('news/{news}/toggle-featured', [\App\Http\Controllers\Admin\NewsController::class, 'toggleFeatured'])->name('news.toggle-featured');
    Route::delete('news/{news}/gallery-image/{imageIndex}', [\App\Http\Controllers\Admin\NewsController::class, 'removeGalleryImage'])->name('news.remove-gallery-image');
});
