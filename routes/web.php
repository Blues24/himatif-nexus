<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DanusSHop\ProdukController;
use App\Http\Controllers\Infokom\FormatifController;
use App\Http\Controllers\Infokom\KegiatanController;
use App\Http\Controllers\Portal\InventarisController;
use App\Http\Controllers\Portal\PenyewaanAlatController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureAdminRole;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/formatif', [FormatifController::class, 'index'])->name('formatif.index');
    Route::get('/formatif/create', [FormatifController::class, 'create'])->name('formatif.create');
    Route::post('/formatif', [FormatifController::class, 'store'])->name('formatif.store');
    Route::get('/formatif/{formatif}/edit', [FormatifController::class, 'edit'])->name('formatif.edit');
    Route::put('/formatif/{formatif}', [FormatifController::class, 'update'])->name('formatif.update');
    Route::delete('/formatif/{formatif}', [FormatifController::class, 'destroy'])->name('formatif.destroy');

    Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

    Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
    Route::post('/inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
    Route::get('/inventaris/{inventaris}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
    Route::put('/inventaris/{inventaris}', [InventarisController::class, 'update'])->name('inventaris.update');
    Route::delete('/inventaris/{inventaris}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');

    Route::get('/penyewaan', [PenyewaanAlatController::class, 'index'])->name('penyewaan.index');
    Route::get('/penyewaan/create', [PenyewaanAlatController::class, 'create'])->name('penyewaan.create');
    Route::post('/penyewaan', [PenyewaanAlatController::class, 'store'])->name('penyewaan.store');
    Route::get('/penyewaan/{penyewaan}/edit', [PenyewaanAlatController::class, 'edit'])->name('penyewaan.edit');
    Route::put('/penyewaan/{penyewaan}', [PenyewaanAlatController::class, 'update'])->name('penyewaan.update');
    Route::delete('/penyewaan/{penyewaan}', [PenyewaanAlatController::class, 'destroy'])->name('penyewaan.destroy');

    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
});

/*
 |--------------------------------------------------------------------------
 | Admin Routes
 |--------------------------------------------------------------------------
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