<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    // Placeholder routes for sidebar navigation
    Route::get('/shop', fn () => Inertia::render('Admin/Dashboard'))->name('shop.index');
    Route::get('/informasi', fn () => Inertia::render('Admin/Dashboard'))->name('informasi.index');
    Route::get('/inventaris', fn () => Inertia::render('Admin/Dashboard'))->name('inventaris.index');
    Route::get('/kegiatan', fn () => Inertia::render('Admin/Dashboard'))->name('kegiatan.index');
});

require __DIR__.'/auth.php';
