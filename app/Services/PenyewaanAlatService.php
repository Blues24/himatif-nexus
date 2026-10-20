<?php

namespace App\Services;

use App\Models\PenyewaanAlat;
use Illuminate\Support\Facades\DB;

class PenyewaanAlatService extends BaseService
{
    protected string $model = PenyewaanAlat::class;

    protected array $fillableColumns = [
        'inventaris_alat_id',
        'user_id',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    /**
     * Eager-load the related equipment and borrower so list and detail views
     * don't trigger N+1 lookups.
     *
     * @var array<string>
     */
    protected array $withRelations = ['inventarisAlat', 'user'];

    /**
     * Create a rental request after verifying stock availability atomically.
     * No stock is deducted yet — that happens on approval.
     */
    public function submitRequest(array $data): object
    {
        $data['status'] = $data['status'] ?? 'pending';

        return DB::transaction(function () use ($data) {
            // Atomic check: fail if there isn't enough stock to reserve.
            $available = (int) DB::selectOne(
                'SELECT stok FROM inventaris_alat WHERE inventaris_id = ? FOR UPDATE',
                [$data['inventaris_alat_id']]
            )?->stok ?? null;

            if ($available === null) {
                throw new \DomainException('Alat inventaris tidak ditemukan');
            }

            if ($available < $data['jumlah_pinjam']) {
                throw new \DomainException('Stok alat tidak mencukupi');
            }

            $record = app($this->model)->create(array_only($data, $this->fillableColumns));

            $this->afterCreate($record, $data);

            return $record;
        });
    }

    /**
     * Approve or reject a rental request. Approval deducts stock atomically.
     */
    public function handleRequest(string|int $id, string $action): bool
    {
        $request = app(PenyewaanAlat::class)->where($this->getKey(), $id)->firstOrFail();

        if (! in_array($action, ['approve', 'reject'])) {
            throw new \InvalidArgumentException('Aksi tidak valid.');
        }

        $statusMap = ['approve' => 'disetujui', 'reject' => 'ditolak'];

        if ($action === 'approve') {
            $affected = DB::update(
                'UPDATE inventaris_alat SET stok = stok - ? WHERE inventaris_id = ? AND stok >= ?',
                [$request->jumlah_pinjam, $request->inventaris_alat_id, $request->jumlah_pinjam]
            );

            if ($affected === 0) {
                throw new \DomainException('Stok tidak mencukupi untuk persetujuan');
            }
        }

        return $request->update(['status' => $statusMap[$action]]);
    }

    /**
     * Mark a rental as returned and restore the stock.
     */
    public function markReturned(string|int $id): bool
    {
        $request = app(PenyewaanAlat::class)->where($this->getKey(), $id)->firstOrFail();

        DB::update(
            'UPDATE inventaris_alat SET stok = stok + ? WHERE inventaris_id = ?',
            [$request->jumlah_pinjam, $request->inventaris_alat_id]
        );

        return $request->update(['status' => 'dikembalikan']);
    }

    /**
     * Resolve the model's primary key without hitting the container twice.
     */
    private function getKey(): string
    {
        return app(PenyewaanAlat::class)->getKeyName();
    }
}
