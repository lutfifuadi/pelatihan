<?php

use App\Http\Controllers\PengumumanPelatihanController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Admin CRUD routes untuk Pengumuman Pelatihan
    Route::apiResource('admin/pengumuman-pelatihan', \App\Http\Controllers\Admin\PengumumanPelatihanController::class);

    // User endpoint: Mengambil pengumuman privat (untuk dashboard kelas)
    Route::get('pelatihan/{pelatihan}/pengumuman/privat', [PengumumanPelatihanController::class, 'getPrivateAnnouncements'])
        ->name('pelatihan.pengumuman.privat');
});

// User endpoint: Mengambil pengumuman publik (untuk halaman depan detail pelatihan)
Route::get('pelatihan/{pelatihan}/pengumuman/publik', [PengumumanPelatihanController::class, 'getPublicAnnouncements'])
    ->name('pelatihan.pengumuman.publik');
