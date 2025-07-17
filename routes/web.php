<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ApiController;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminCategoryController;

// ─── PUBLIC ROUTES ─────────────────────────────────────────────
Route::get('/', [HomepageController::class, 'index'])->name('home');
Route::get('products', [HomepageController::class, 'products']);
Route::get('product/{slug}', [HomepageController::class, 'product'])->name('product.show');
Route::get('categories', [HomepageController::class, 'categories']);
Route::get('category/{slug}', [HomepageController::class, 'category']);
Route::get('cart', [HomepageController::class, 'cart'])->name('cart.index');
Route::get('checkout', [HomepageController::class, 'checkout'])->name('checkout.index');

// ─── CUSTOMER CART ─────────────────────────────────────────────
Route::group(['middleware' => ['is_customer_login']], function () {
    Route::controller(CartController::class)->group(function () {
        Route::post('cart/add', 'add')->name('cart.add');
        Route::delete('cart/remove/{id}', 'remove')->name('cart.remove');
        Route::patch('cart/update/{id}', 'update')->name('cart.update');
    });
});

// ─── CUSTOMER AUTH ─────────────────────────────────────────────
Route::prefix('customer')->controller(CustomerAuthController::class)->group(function () {
    Route::middleware('check_customer_login')->group(function () {
        Route::get('login', 'login')->name('customer.login');
        Route::post('login', 'store_login')->name('customer.store_login');
        Route::get('register', 'register')->name('customer.register');
        Route::post('register', 'store_register')->name('customer.store_register');
    });

    Route::post('logout', 'logout')->name('customer.logout');
});

// ─── CHECKOUT ─────────────────────────────────────────────
Route::middleware('is_customer_login')->group(function () {
    Route::get('checkout', [CheckoutController::class, 'show'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});

// ─── DASHBOARD (ADMIN SYSTEM DEFAULT) ─────────────────────────────

Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // ...rute admin lain
});

Route::get('/redirect-role', function () {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect('/admin');
    }

    return redirect('/');
})->middleware('auth');

    // CRUD
    Route::resource('categories', ProductCategoryController::class)->names('dashboard.categories');
    Route::resource('products', ProductController::class)->names('dashboard.products');
    Route::resource('themes', ThemeController::class);

    // Sinkronisasi Produk & Kategori (Versi Default)
    Route::post('products/sync/{id}', [ProductController::class, 'sync'])->name('products.sync');
    Route::post('category/sync/{id}', [ProductCategoryController::class, 'sync'])->name('category.sync');

// ─── ADMIN PANEL ROUTES (Custom Admin UI) ─────────────────────────────
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Dashboard admin pakai controller
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin Produk
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('/products/{id}/sync', [AdminProductController::class, 'sync'])->name('products.sync');

    // Admin Kategori
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/{id}/sync', [AdminCategoryController::class, 'sync'])->name('categories.sync');
});

// ─── SETTINGS (Volt UI) ─────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// ─── DEFAULT AUTH ─────────────────────────────────────────────
require __DIR__.'/auth.php';
