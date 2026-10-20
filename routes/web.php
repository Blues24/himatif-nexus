<?php

use App\Http\Controllers\DanusSHop\ProdukController;
use App\Http\Controllers\Portal\InventarisController;
use App\Http\Controllers\Portal\PenyewaanAlatController;
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

// Danus Shop: public browse, auth-restricted writes
Route::controller(ProdukController::class)->group(function () {
    Route::get('/produk', 'index')->name('produk.index');
    Route::middleware('auth')->group(function () {
        Route::get('/produk/create', 'create')->name('produk.create');
        Route::post('/produk', 'store')->name('produk.store');
        Route::get('/produk/{id}/edit', 'edit')->name('produk.edit');
        Route::patch('/produk/{id}', 'update')->name('produk.update');
        Route::delete('/produk/{id}', 'destroy')->name('produk.destroy');
    });
    // Declared last so the literal /produk/create segments win against {id}
    Route::get('/produk/{id}', 'show')->whereNumber('id')->name('produk.show');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Portal: Inventory management (auth required)
Route::middleware('auth')->group(function () {
    Route::controller(InventarisController::class)->prefix('inventaris')->name('inventaris.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::patch('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::controller(PenyewaanAlatController::class)->prefix('penyewaan')->name('penyewaan.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::post('/{id}/approve', 'approve')->name('approve');
        Route::post('/{id}/reject', 'reject')->name('reject');
        Route::post('/{id}/return', 'markReturned')->name('return');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
