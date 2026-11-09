<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\DanusSHop\ProdukController;
use App\Http\Controllers\Infokom\FormatifController;
use App\Http\Controllers\Infokom\KegiatanController;
use App\Http\Controllers\Portal\InventarisController;
use App\Http\Controllers\Portal\PenyewaanAlatController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureAdminRole;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('beranda');

Route::get('/beranda', function () {
    return Inertia::render('Dashboard');
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

// Formatif (Blog): public browse, auth-restricted writes
Route::controller(FormatifController::class)->group(function () {
    Route::get('/formatif', 'index')->name('formatif.index');
    Route::get('/formatif/published', 'published')->name('formatif.published');
    Route::middleware('auth')->group(function () {
        Route::get('/formatif/create', 'create')->name('formatif.create');
        Route::post('/formatif', 'store')->name('formatif.store');
        Route::get('/formatif/{id}/edit', 'edit')->name('formatif.edit');
        Route::patch('/formatif/{id}', 'update')->name('formatif.update');
        Route::delete('/formatif/{id}', 'destroy')->name('formatif.destroy');
    });
    // Declared last so the literal /formatif/create segments win against {id}
    Route::get('/formatif/{id}', 'show')->whereNumber('id')->name('formatif.show');
});

// Kegiatan: public browse, auth-restricted writes
Route::controller(KegiatanController::class)->group(function () {
    Route::get('/kegain', 'index')->name('kegain.index');
    Route::get('/kegain/{id}', 'show')->whereNumber('id')->name('kegain.show');
    Route::middleware('auth')->group(function () {
        Route::get('/kegain/create', 'create')->name('kegain.create');
        Route::post('/kegain', 'store')->name('kegain.store');
        Route::get('/kegain/{id}/edit', 'edit')->name('kegain.edit');
        Route::patch('/kegain/{id}', 'update')->name('kegain.update');
        Route::delete('/kegain/{id}', 'destroy')->name('kegain.destroy');
    });
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

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

/*
 |--------------------------------------------------------------------------
 | Admin Routes
 |--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/dashboard', fn () => Inertia::render('Admin/Dashboard'))->name('dashboard.index');
    Route::get('/shop', fn () => Inertia::render('Admin/DanusShop'))->name('shop.index');
    Route::get('/formatif', fn () => Inertia::render('Admin/Formatif'))->name('formatif.index');
    Route::get('/inventaris', fn () => Inertia::render('Admin/Inventaris'))->name('inventaris.index');
    Route::get('/kegain', fn () => Inertia::render('Admin/Kegiatan'))->name('kegain.index');
    Route::get('/hak-akses', fn () => Inertia::render('Admin/HakAkses'))->name('hak-akses.index');
});

require __DIR__.'/auth.php';