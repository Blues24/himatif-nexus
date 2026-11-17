<?php

namespace App\Providers;

use App\Models\Formatif;
use App\Models\Inventaris;
use App\Models\Kegiatan;
use App\Models\PenyewaanAlat;
use App\Models\Produk;
use App\Policies\FormatifPolicy;
use App\Policies\InventarisPolicy;
use App\Policies\KegiatanPolicy;
use App\Policies\PenyewaanAlatPolicy;
use App\Policies\ProdukPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Kegiatan::class, KegiatanPolicy::class);
        Gate::policy(Formatif::class, FormatifPolicy::class);
        Gate::policy(Produk::class, ProdukPolicy::class);
        Gate::policy(Inventaris::class, InventarisPolicy::class);
        Gate::policy(PenyewaanAlat::class, PenyewaanAlatPolicy::class);

        Vite::prefetch(concurrency: 3);
    }
}