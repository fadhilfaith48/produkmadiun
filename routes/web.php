<?php

use Illuminate\Support\Facades\Route;

// ============================================================
// CONTROLLER PUBLIK
// ============================================================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;

// ============================================================
// CONTROLLER UMKM
// ============================================================
use App\Http\Controllers\Umkm\DashboardController as UmkmDashboard;
use App\Http\Controllers\Umkm\ProductController as UmkmProduct;
use App\Http\Controllers\Umkm\OrderController as UmkmOrder;

// ============================================================
// CONTROLLER ADMIN
// ============================================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

// ============================================================
// HALAMAN PUBLIK
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/katalog',        [ProductController::class, 'index'])->name('products.index');
Route::get('/katalog/{slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/toko',        [StoreController::class, 'index'])->name('stores.index');
Route::get('/toko/{slug}', [StoreController::class, 'show'])->name('stores.show');

Route::post('/ulasan', [ReviewController::class, 'store'])->name('reviews.store');

// ============================================================
// KERANJANG
// ============================================================
Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/',              [CartController::class, 'index'])->name('index');
    Route::post('/tambah',       [CartController::class, 'add'])->name('add');
    Route::put('/update/{id}',   [CartController::class, 'update'])->name('update');
    Route::delete('/hapus/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/kosongkan',  [CartController::class, 'clear'])->name('clear');
});

// ============================================================
// PESANAN
// ============================================================
Route::prefix('pesan')->name('order.')->group(function () {
    Route::get('/checkout',      [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/simpan',       [OrderController::class, 'store'])->name('store');
    Route::get('/sukses/{code}', [OrderController::class, 'success'])->name('success');
    Route::get('/lacak',         [OrderController::class, 'track'])->name('track');
    Route::post('/lacak',        [OrderController::class, 'trackSearch'])->name('track.search');
    Route::get('/whatsapp/{id}', [OrderController::class, 'whatsapp'])->name('whatsapp');
});

// ============================================================
// AUTH
// ============================================================

// Login & Logout
Route::get('/login',  [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout',[App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Register
Route::get('/register',  [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Lupa Password
Route::get('/password/reset',         [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email',        [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset',        [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Verifikasi Email
Route::middleware('auth')->group(function () {
    Route::get('/email/verify',                [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
    Route::post('/email/resend',               [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');
    Route::get('/email/verify/{id}/{hash}',    [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
});

// ============================================================
// PANEL UMKM
// ============================================================
Route::prefix('umkm')->name('umkm.')->middleware(['auth'])->group(function () {

    // Dashboard & Profil Toko
    Route::get('/dashboard',   [UmkmDashboard::class, 'index'])->name('dashboard');
    Route::get('/profil-toko', [UmkmDashboard::class, 'profile'])->name('profile');
    Route::put('/profil-toko', [UmkmDashboard::class, 'updateProfile'])->name('profile.update');

    // Manajemen Produk
    Route::prefix('produk')->name('products.')->group(function () {
        Route::get('/',              [UmkmProduct::class, 'index'])->name('index');
        Route::get('/tambah',        [UmkmProduct::class, 'create'])->name('create');
        Route::post('/',             [UmkmProduct::class, 'store'])->name('store');
        Route::get('/{id}/edit',     [UmkmProduct::class, 'edit'])->name('edit');
        Route::put('/{id}',          [UmkmProduct::class, 'update'])->name('update');
        Route::delete('/{id}',       [UmkmProduct::class, 'destroy'])->name('destroy');
        Route::post('/{id}/foto',    [UmkmProduct::class, 'uploadImage'])->name('upload-image');
        Route::delete('/foto/{imageId}', [UmkmProduct::class, 'deleteImage'])->name('delete-image');
    });

    // Manajemen Pesanan
    Route::prefix('pesanan')->name('orders.')->group(function () {
        Route::get('/',              [UmkmOrder::class, 'index'])->name('index');
        Route::get('/{id}',          [UmkmOrder::class, 'show'])->name('show');
        Route::put('/{id}/status',   [UmkmOrder::class, 'updateStatus'])->name('update-status');
    });
});

// ============================================================
// PANEL ADMIN
// ============================================================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::put('/ulasan/{id}/approve', [AdminDashboard::class, 'approveReview'])->name('reviews.approve');

    // Kelola Toko
    Route::get('/toko', [AdminDashboard::class, 'stores'])->name('stores');
    Route::put('/toko/{id}/verifikasi', [AdminDashboard::class, 'verifyStore'])->name('stores.verify');
    Route::put('/toko/{id}/batalkan', [AdminDashboard::class, 'unverifyStore'])->name('stores.unverify');
});