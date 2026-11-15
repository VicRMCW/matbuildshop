<?php

use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

// 1. Publik: Beranda, Katalog & halaman detail produk toko
Route::get('/', [ShopController::class, 'home'])->name('shop.home');
Route::get('/katalog', [ShopController::class, 'index'])->name('shop.catalog');
Route::get('/produk/{product:slug}', [ShopController::class, 'show'])->name('shop.products.show');

// 2. Guest: Autentikasi pendaftaran dan masuk (khusus pengguna belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    // Batasi percobaan login guna mencegah brute-force attack.
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

// 3. Auth: Sesi aktif semua role
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// 4. Customer: Area khusus pelanggan
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Keranjang belanja
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/keranjang/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

        // Checkout dan pesanan
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
        // Beli sekarang (tanpa keranjang)
    Route::post('/beli-langsung', [CheckoutController::class, 'startDirect'])->name('checkout.direct.start');
    Route::get('/checkout/langsung', [CheckoutController::class, 'direct'])->name('checkout.direct');
    Route::post('/checkout/langsung', [CheckoutController::class, 'storeDirect'])->name('checkout.direct.store');
    Route::get('/pesanan/{order:order_code}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order:order_code}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
});


// 5. Admin: Area khusus administrator toko
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except('show');
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except('show');
        Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/pesanan/{order:order_code}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/pesanan/{order:order_code}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    });

// Webhook Midtrans: dipanggil server Midtrans, jadi tanpa login dan tanpa CSRF.
// Keamanannya lewat verifikasi signature_key di controller.
Route::post('/midtrans/notification', [MidtransNotificationController::class, 'handle'])
    ->name('midtrans.notification'); 