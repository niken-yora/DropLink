<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ROUTE PUBLIC: Siapa aja bisa akses link ini (Satpamnya nanti ada di Controller)
Route::get('/media/{media}', [MediaController::class, 'show'])->name('media.show');

// ROUTE PRIVATE: Wajib Login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [MediaController::class, 'index'])->name('dashboard');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    
    // Tambahan baru: Route untuk Hapus File
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';