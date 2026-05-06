<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ─── Auth (public) ────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

// ─── Protected routes ─────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me',     [AuthController::class, 'me']);
    });

    // Produk
    Route::apiResource('products', ProductController::class);

    // Kategori
    Route::apiResource('kategori', KategoriController::class);

    // Transaksi
    Route::get('transaksi',             [TransaksiController::class, 'index']);
    Route::get('transaksi/{transaksi}', [TransaksiController::class, 'show']);
    Route::post('transaksi',            [TransaksiController::class, 'store']);

    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/ringkasan', [LaporanController::class, 'ringkasan']);
        Route::get('/transaksi', [LaporanController::class, 'transaksi']);
        Route::get('/harian',    [LaporanController::class, 'harian']);
        Route::get('/bulanan',   [LaporanController::class, 'bulanan']);
        Route::get('/rangkuman', [LaporanController::class, 'rangkuman']);
    });

    // Users
    Route::apiResource('users', UserController::class);
});