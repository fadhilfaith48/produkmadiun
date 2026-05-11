<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublikController;

// ================================
// API PUBLIK — untuk Madiun Info Hub
// Tidak perlu login / token
// ================================

Route::prefix('publik')->group(function () {

    // Produk
    Route::get('/produk',         [PublikController::class, 'produk']);
    Route::get('/produk/{id}',    [PublikController::class, 'detailProduk']);

    // UMKM / Toko
    Route::get('/umkm',           [PublikController::class, 'umkm']);
    Route::get('/umkm/{id}',      [PublikController::class, 'detailUmkm']);

    // Filter & referensi
    Route::get('/kategori',       [PublikController::class, 'kategori']);
    Route::get('/kecamatan',      [PublikController::class, 'kecamatan']);

    // Statistik
    Route::get('/statistik',      [PublikController::class, 'statistik']);

});