<?php

namespace App\Services;

use App\Models\PenyewaanAlat;

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

    protected array $withRelations = ['inventarisAlat', 'user'];

    /**
     * Submit a new rental request with stock verification.
     */
    public function submitRequest(array $data): object
    {
        // Verify stock availability before creating the request
        $inventaris = app(Inventaris::class)->find($data['inventaris_alat_id']);

        if (! $inventaris || $inventaris->stok < $data['jumlah_pinjam']) {
            throw new \DomainException('Stok alat tidak mencukupi');
        }

        // Set initial status to pending review
        $data['status'] = $data['status'] ?? 'pending';

        return parent::create($data);
    }

    /**
     * Approve or reject a rental request.
     */
    public function handleRequest(string|int $id, string $action): bool
    {
        $primaryKey = app(PenyewaanAlat::class)->getKeyName();
        $request = app(PenyewaanAlat::class)->where($primaryKey, $id)->firstOrFail();

        if (! in_array($action, ['approve', 'reject'])) {
            throw new \InvalidArgumentException("Aksi tidak valid: {$action}");
        }

        // For approvals, deduct stock; for rejections, no-op
        if ($action === 'approve') {
            $inventaris = $request->inventarisAlat;
            if ($inventaris) {
                $inventaris->decrement('stok', $request->jumlah_pinjam);
            }
        }

        return $request->update([
            'status' => $action === 'approve' ? 'disetujui' : 'ditolak',
        ]);
    }

    /**
     * Mark a rental as returned (increment stock back).
     */
    public function markReturned(string|int $id): bool
    {
        $primaryKey = app(PenyewaanAlat::class)->getKeyName();
        $request = app(PenyewaanAlat::class)->where($primaryKey, $id)->firstOrFail();

        $inventaris = $request->inventarisAlat;
        if ($inventaris) {
            $inventaris->increment('stok', $request->jumlah_pinjam);
        }

        return $request->update(['status' => 'dikembalikan']);
    }
}
