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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

// Warta & Edukasi Informasi Publik
Route::get('/informasi', function () {
    return Inertia::render('Informasi');
})->name('informasi');

Route::get('/informasi/{id}', function (int $id) {
    return Inertia::render('InformasiDetail', [
        'id' => $id,
    ]);
})->whereNumber('id')->name('informasi.show');

// Penyewaan Inventaris Publik
Route::get('/penyewaan-inventaris', function () {
    return Inertia::render('PenyewaanInventaris');
})->name('penyewaan-inventaris');

Route::get('/penyewaan-alat', function () {
    return redirect()->route('penyewaan-inventaris');
});

// Kegiatan Publik (Index & Detail)
Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/{id}', [KegiatanController::class, 'show'])->whereNumber('id')->name('kegiatan.show');

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
| Penyewaan Alat - public browse, auth-restricted writes
|--------------------------------------------------------------------------|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kegiatan Admin/Auth Writes
    Route::get('/kegiatan/create', [KegiatanController::class, 'create'])->name('kegiatan.create');
    Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
    Route::get('/kegiatan/{kegiatan}/edit', [KegiatanController::class, 'edit'])->name('kegiatan.edit');
    Route::put('/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('kegiatan.update');
    Route::delete('/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('kegiatan.destroy');

    // Inventaris CRUD
    Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
    Route::post('/inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
    Route::get('/inventaris/{inventaris}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
    Route::put('/inventaris/{inventaris}', [InventarisController::class, 'update'])->name('inventaris.update');
    Route::delete('/inventaris/{inventaris}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');

    // Penyewaan Alat CRUD
    Route::get('/penyewaan', [PenyewaanAlatController::class, 'index'])->name('penyewaan.index');
    Route::get('/penyewaan/create', [PenyewaanAlatController::class, 'create'])->name('penyewaan.create');
    Route::post('/penyewaan', [PenyewaanAlatController::class, 'store'])->name('penyewaan.store');
    Route::get('/penyewaan/{penyewaan}/edit', [PenyewaanAlatController::class, 'edit'])->name('penyewaan.edit');
    Route::put('/penyewaan/{penyewaan}', [PenyewaanAlatController::class, 'update'])->name('penyewaan.update');
    Route::delete('/penyewaan/{penyewaan}', [PenyewaanAlatController::class, 'destroy'])->name('penyewaan.destroy');
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
    Route::get('/informasi', fn () => Inertia::render('Admin/Dashboard'))->name('informasi.index');
    Route::get('/inventaris', fn () => Inertia::render('Admin/Inventaris'))->name('inventaris.index');
    Route::get('/kegiatan', fn () => Inertia::render('Admin/Kegiatan'))->name('kegiatan.index');

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
