<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
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

    /**
     * produk has no foreign keys in its migration, so there is nothing to
     * eager-load. ShopDetail::class and Produk::shopDetails() reference a
     * `shop_detail` table no migration creates; do not eager-load it here
     * until that schema lands.
     *
     * @var array<string>
     */
    protected array $withRelations = [];

    /**
     * Case-insensitive name search. LOWER() keeps the SQL portable across
     * Postgres and MySQL, where LIKE differs in case sensitivity.
     */
    public function search(string $keyword, int $perPage = 15): LengthAwarePaginator
    {
        return $this->paginate(
            fn (Builder $query) => $query->whereRaw('LOWER(nama_produk) LIKE ?', ['%'.mb_strtolower($keyword).'%']),
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
