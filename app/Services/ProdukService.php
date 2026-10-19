<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

    /**
     * Search products by name using LIKE. Case-insensitive.
     */
    public function search(string $keyword, int $perPage = 15)
    {
        return $this->paginate(
            fn (Produk $q) => $q->where('nama_produk', 'like', "%{$keyword}%"),
            $perPage
        );
    }

    /**
     * Products whose stock falls at or below the given threshold.
     */
    public function getLowStock(int $threshold = 5): Collection
    {
        return app(Produk::class)
            ->with($this->withRelations)
            ->where('stok_produk', '<=', $threshold)
            ->orderBy('stok_produk', 'asc')
            ->get();
    }

    /**
     * Adjust stock quantity atomically. Fails if deduction exceeds available stock.
     * Uses raw UPDATE so the check-and-write cannot be interleaved by another request.
     */
    public function adjustStock(int $produkId, int $quantityDelta): bool
    {
        $affected = DB::update(
            'UPDATE produk SET stok_produk = stok_produk + ? WHERE produk_id = ? AND stok_produk + ? >= 0',
            [$quantityDelta, $produkId, $quantityDelta]
        );

        if ($affected === 0) {
            // Zero rows means either the product doesn't exist or stock was insufficient
            $exists = DB::selectOne(
                'SELECT produk_id FROM produk WHERE produk_id = ?',
                [$produkId]
            );

            throw new \DomainException($exists !== null ? 'Stok tidak mencukupi' : 'Produk tidak ditemukan');
        }

        return true;
    }
}
