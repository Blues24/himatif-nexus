<?php

namespace App\Services;

use App\Models\Inventaris;

class InventarisService extends BaseService
{
    protected string $model = Inventaris::class;

    protected array $fillableColumns = [
        'nama_barang',
        'stok',
        'kondisi',
    ];

    protected array $withRelations = ['penyewaanAlats'];

    /**
     * Retrieve items matching a condition (baik, rusak, hilang).
     */
    public function byCondition(string $condition)
    {
        return $this->paginate(
            fn (Inventaris $q) => $q->where('kondisi', $condition)
        );
    }

    /**
     * Adjust inventory quantity with availability check.
     */
    public function adjustQuantity(int $inventarisId, int $delta): bool
    {
        $item = $this->findOrFail($inventarisId);
        $newQty = max(0, $item->stok + $delta);

        $item->update(['stok' => $newQty]);

        return true;
    }
}
