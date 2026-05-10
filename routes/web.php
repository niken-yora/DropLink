<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    return view('welcome');
});

// Public file access
Route::get('/media/{media}', [MediaController::class, 'show'])
    ->name('media.show');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USER DASHBOARD
    |--------------------------------------------------------------------------
    */

    // Dashboard
    Route::get('/dashboard', [MediaController::class, 'index'])
        ->name('dashboard');

    // Upload media
    Route::post('/media', [MediaController::class, 'store'])
        ->name('media.store');

    // Delete own media
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])
        ->name('media.destroy');


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL
    |--------------------------------------------------------------------------
    */

    Route::middleware(['admin'])->group(function () {

        // Admin dashboard
        Route::get('/admin', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        // Delete user
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])
            ->name('admin.users.destroy');

        // Delete media
        Route::delete('/admin/media/{media}', [AdminController::class, 'destroyMedia'])
            ->name('admin.media.destroy');
    });

});

require __DIR__.'/auth.php';