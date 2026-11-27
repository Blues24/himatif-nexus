<?php

namespace App\Observers;

use App\Jobs\CompressImageJob;
use App\Models\Produk;
use Illuminate\Support\Facades\Log;

class ProdukObserver
{
    public function created(Produk $produk): void
    {
        $this->maybeCompress($produk->gambar_produk);
    }

    public function updated(Produk $produk): void
    {
        if ($produk->wasChanged('gambar_produk')) {
            $this->maybeCompress($produk->gambar_produk);
        }
    }

    protected function maybeCompress(?string $path): void
    {
        if (! $path) {
            return;
        }

        Log::info('[ProdukObserver] dispatching compression for '.$path);
        dispatch(new CompressImageJob($path));
    }

    public function deleted(Produk $produk): void {}
}
