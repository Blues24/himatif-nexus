<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DanusSHop\ProdukController;
use App\Http\Controllers\Infokom\FormatifController;
use App\Http\Controllers\Infokom\KegiatanController;
use App\Http\Controllers\NotificationStreamController;
use App\Http\Controllers\Portal\InventarisController;
use App\Http\Controllers\Portal\PenyewaanAlatController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureAdminRole;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------|
| Public Routes
|--------------------------------------------------------------------------|
*/

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('beranda');

Route::get('/beranda', function () {
    return Inertia::render('Dashboard');
})->name('beranda.alt');

/*
|--------------------------------------------------------------------------|
| Authenticated Routes
|--------------------------------------------------------------------------|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------|
    | Notification SSE + JSON
    |--------------------------------------------------------------------------|
    */
    Route::get('/notifications', [NotificationStreamController::class, 'index'])
        ->middleware(EnsureSseAuth::class)
        ->name('notifications.index');

    Route::get('/notifications/stream', [NotificationStreamController::class, 'stream'])
        ->middleware(EnsureSseAuth::class)
        ->name('notifications.stream');
});

/*
|--------------------------------------------------------------------------|
| Danus Shop
|--------------------------------------------------------------------------|
*/

Route::controller(ProdukController::class)->group(function () {
    Route::get('/produk', 'index')->name('produk.index');

    Route::middleware('auth')->group(function () {
        Route::get('/produk/create', 'create')->name('produk.create');
        Route::post('/produk', 'store')->name('produk.store');
        Route::get('/produk/{id}/edit', 'edit')->name('produk.edit');
        Route::patch('/produk/{id}', 'update')->name('produk.update');
        Route::delete('/produk/{id}', 'destroy')->name('produk.destroy');
    });

    Route::get('/produk/{id}', 'show')->whereNumber('id')->name('produk.show');
});

/*
|--------------------------------------------------------------------------|
| Formatif (Blog) - public browse, auth-restricted writes
|--------------------------------------------------------------------------|
*/

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

    Route::get('/formatif/{id}', 'show')->whereNumber('id')->name('formatif.show');
});

/*
|--------------------------------------------------------------------------|
| Kegiatan - public browse, auth-restricted writes
|--------------------------------------------------------------------------|
*/

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

/*
|--------------------------------------------------------------------------|
| Inventaris - public browse, auth-restricted writes
|--------------------------------------------------------------------------|
*/

Route::controller(InventarisController::class)->group(function () {
    Route::get('/inventaris', 'index')->name('inventaris.index');

    Route::middleware('auth')->group(function () {
        Route::get('/inventaris/create', 'create')->name('inventaris.create');
        Route::post('/inventaris', 'store')->name('inventaris.store');
        Route::get('/inventaris/{id}/edit', 'edit')->name('inventaris.edit');
        Route::patch('/inventaris/{id}', 'update')->name('inventaris.update');
        Route::delete('/inventaris/{id}', 'destroy')->name('inventaris.destroy');
    });
});

/*
|--------------------------------------------------------------------------|
| Penyewaan Alat - public browse, auth-restricted writes
|--------------------------------------------------------------------------|
*/

Route::controller(PenyewaanAlatController::class)->group(function () {
    Route::get('/penyewaan', 'index')->name('penyewaan.index');

    Route::middleware('auth')->group(function () {
        Route::get('/penyewaan/create', 'create')->name('penyewaan.create');
        Route::post('/penyewaan', 'store')->name('penyewaan.store');
        Route::get('/penyewaan/{id}/edit', 'edit')->name('penyewaan.edit');
        Route::patch('/penyewaan/{id}', 'update')->name('penyewaan.update');
        Route::delete('/penyewaan/{id}', 'destroy')->name('penyewaan.destroy');
    });
});

/*
|--------------------------------------------------------------------------|
| Admin Routes
|--------------------------------------------------------------------------|
*/

Route::prefix('admin')->name('admin.')->middleware([EnsureAdminRole::class])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/dashboard', fn () => Inertia::render('Admin/Dashboard'))->name('dashboard.index');
    Route::get('/shop', fn () => Inertia::render('Admin/DanusShop'))->name('shop.index');
    Route::get('/formatif', fn () => Inertia::render('Admin/Formatif'))->name('formatif.index');
    Route::get('/inventaris', fn () => Inertia::render('Admin/Inventaris'))->name('inventaris.index');
    Route::get('/kegain', fn () => Inertia::render('Admin/Kegiatan'))->name('kegain.index');

    Route::resource('hak-akses', UserController::class)
        ->names([
            'index' => 'hak-akses.index',
            'store' => 'hak-akses.store',
            'update' => 'hak-akses.update',
            'destroy' => 'hak-akses.destroy',
        ])
        ->parameters(['hak-akses' => 'user'])
        ->only(['index', 'store', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
