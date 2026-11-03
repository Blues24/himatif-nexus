<?php

namespace App\Services;

use App\Models\Inventaris;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InventarisService extends BaseService
{
    protected string $model = Inventaris::class;

    protected array $fillableColumns = [
        'nama_barang',
        'stok',
        'kondisi',
    ];

    /**
     * `inventaris_alat` has no timestamps; eager-loading penyewaanAlats
     * references a stub migration. Keep this empty until the schema is correct.
     *
     * @var array<string>
     */
    protected array $withRelations = [];

    /**
     * Items grouped by condition for the filter view.
     */
    public function byCondition(string $condition): LengthAwarePaginator
    {
        return $this->paginate(
            fn ($q) => $q->where('kondisi', $condition)
        );
    }

    /**
     * Atomic stock adjustment using a single UPDATE. The WHERE clause
     * guards against the new stock going negative, so two concurrent
     * requests cannot oversell the same item.
     *
     * @throws \DomainException when stock would go below zero
     * @throws ModelNotFoundException when item missing
     */
    public function adjustStock(int $inventarisId, int $delta): bool
    {
        $affected = DB::update(
            'UPDATE inventaris_alat SET stok = stok + ? WHERE inventaris_id = ? AND stok + ? >= 0',
            [$delta, $inventarisId, $delta]
        );

        if ($affected === 0) {
            // Distinguish "missing item" from "would go negative" via a follow-up read.
            $exists = DB::table('inventaris_alat')->where('inventaris_id', $inventarisId)->exists();

            throw new \DomainException(
                $exists ? 'Stok tidak mencukupi' : 'Alat inventaris tidak ditemukan'
            );
        }

        return true;
    }
}
