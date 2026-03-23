<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\RatingController as AdminRatingController;
use App\Http\Controllers\Admin\SalesAnalyticsController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\VariantController as AdminVariantController;
use Illuminate\Support\Facades\Route;


// Public home page
Route::get('/', [HomeController::class, 'index'])->name('home');


// Publicly accessible product and category browsing
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [ProductController::class, 'categoriesIndex'])->name('categories.index');
Route::get('/categories/{category}/products', [ProductController::class, 'byCategory'])->name('categories.products');

// Protected dashboard route
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::post('/products/{product}/ratings', [RatingController::class, 'store'])->name('products.ratings.store');
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

        // Favorites routes
        Route::get('/favorites', [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorites.index');
        Route::post('/favorites', [\App\Http\Controllers\FavoriteController::class, 'store'])->name('favorites.store');
        Route::delete('/favorites/{product}', [\App\Http\Controllers\FavoriteController::class, 'destroy'])->name('favorites.destroy');
    
    
    // Order routes
    Route::post('/buy-now', [OrderController::class, 'buyNow'])->name('orders.buyNow');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');
    Route::patch('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

Route::middleware(['auth', 'active', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    Route::patch('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::post('/products/import', [AdminProductController::class, 'import'])->name('products.import');
    Route::patch('/products/{product}/restore', [AdminProductController::class, 'restore'])->withTrashed()->name('products.restore');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    Route::resource('ratings', AdminRatingController::class)->only(['index', 'destroy']);
    Route::get('/analytics/sales', [SalesAnalyticsController::class, 'index'])->name('analytics.sales');
    Route::patch('/home/featured-image', [HomeController::class, 'updateFeaturedImage'])->name('home.featured-image.update');
    
    Route::post('/products/{product}/variants', [AdminVariantController::class, 'store'])->name('products.variants.store');
    Route::patch('/products/{product}/variants/{variant}', [AdminVariantController::class, 'update'])->name('products.variants.update');
    Route::delete('/products/{product}/variants/{variant}', [AdminVariantController::class, 'destroy'])->name('products.variants.destroy');
});

require __DIR__.'/auth.php';
