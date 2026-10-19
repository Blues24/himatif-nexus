<?php

namespace App\Services;

use App\Models\Produk;

class ProdukService extends BaseService
{
    protected string $model = Produk::class;

    protected array $fillableColumns = [
        'nama_produk',
        'deskripsi_produk',
        'harga_produk',
        'stok_produk',
        'gambar_produk',
    ];

    protected array $withRelations = ['shopDetails'];

    public function byStatus(string $status)
    {
        return $this->paginate(fn (Produk $q) => $q->where('status_produk', $status));
    }

    /**
     * Check and adjust stock when a transaction modifies quantities.
     */
    public function adjustStock(int $produkId, int $quantityDelta): bool
    {
        $produk = $this->findOrFail($produkId);
        $newStock = max(0, $produk->stok_produk + $quantityDelta);

        if ($newStock < 0) {
            throw new \DomainException('Stok tidak mencukupi');
        }

        $produk->update(['stok_produk' => $newStock]);

        return true;
    }
}
