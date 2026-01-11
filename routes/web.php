<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

use App\Http\Controllers\Admin\DashboardController;

// Import All Controllers

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;;
use App\Http\Controllers\Auth\GoogleController;;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\MidtransNotificationController;;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', function () {return view('tentang');});

// Catalog
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/search', [CatalogController::class, 'search'])->name('catalog.search');
Route::get('/catalog/category/{category}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/catalog/product/{product}', [CatalogController::class, 'show'])->name('catalog.product');
Route::get('/product/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// Auth Routes (Login, Register, dsb)
Auth::routes();
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.post');

// Google Auth
Route::controller(GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirect')->name('auth.google');
    Route::get('auth/google/callback', 'callback');
});
Route::get('/auth/github', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToGithub'])
    ->name('auth.github');

Route::get('/auth/github/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleGithubCallback'])
    ->name('auth.github.callback');

// Midtrans Webhook (Must be Public)
Route::post('midtrans/notification', [MidtransNotificationController::class, 'handle'])->name('midtrans.notification');

/*
|--------------------------------------------------------------------------
| Customer Routes (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.destroy');
        Route::delete('/banner', [ProfileController::class, 'deleteBanner'])->name('banner.destroy');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
    });

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::patch('/{item}', [CartController::class, 'update'])->name('update');
        Route::delete('/{item}', [CartController::class, 'remove'])->name('remove');
    });

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout & Payment
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/direct', [CheckoutController::class, 'directCheckout'])->name('checkout.direct')->middleware('auth');


    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/pay', [PaymentController::class, 'show'])->name('pay');
        Route::get('/{order}/snap-token', [PaymentController::class, 'getSnapToken'])->name('snap-token');
        Route::get('/{order}/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/{order}/pending', [PaymentController::class, 'pending'])->name('pending');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Auth + Admin Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Produk & Kategori
    Route::resource('products', AdminProductController::class);
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // Image Handling
    Route::delete('/product-images/{image}', [AdminProductController::class, 'destroyImage'])->name('product-images.destroy');

    // Manajemen Pesanan (Status Update Fixed)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');

        // Route ini yang sebelumnya Error 403 karena bentrok
        Route::patch('/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/export-sales', [ReportController::class, 'exportSales'])->name('export-sales');
    });
});
