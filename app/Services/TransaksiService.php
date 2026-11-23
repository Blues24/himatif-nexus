<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TransaksiService extends BaseService
{
    protected string $model = Transaksi::class;

    protected array $fillableColumns = [
        'user_id',
        'total_bayar',
        'status_transaksi',
    ];

    protected array $withRelations = ['user'];

    /**
     * Create transaction inside a DB transaction scope.
     */
    public function record(array $data): object
    {
        return parent::create($data);
    }

    /**
     * Filter by payment status.
     */
    public function byStatus(string $status): LengthAwarePaginator
    {
        return $this->paginate(
            fn (\Illuminate\Database\Eloquent\Builder|Transaksi $q) => $q->where('status_transaksi', $status)
        );
    }

    /**
     * Confirm a pending transaction.
     */
    public function confirm(int|string $id): bool
    {
        $primaryKey = app(Transaksi::class)->getKeyName();
        $transaction = app(Transaksi::class)->where($primaryKey, $id)->firstOrFail();

        if ($transaction->status_transaksi !== 'menunggu') {
            throw new \DomainException('Transaksi tidak dalam status menunggu');
        }

        return $transaction->update([
            'status_transaksi' => 'selesai',
        ]);
    }

    /**
     * Recent transactions ordered by created_at desc, limited to $limit rows.
     *
     * @return Collection<int, object>
     */
    public function recent(int $limit = 5): Collection
    {
        return app(Transaksi::class)
            ->with('user')
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn (Transaksi $t) => (object) [
                'id' => $t->transaksi_id,
                'no_pesanan' => 'ORD-'.str_pad((string) $t->transaksi_id, 6, '0', STR_PAD_LEFT),
                'nama_produk' => $t->user?->nama ?? 'Pelanggan',
                'total' => $t->total_bayar,
                'status' => match ($t->status_transaksi) {
                    'menunggu' => 'Menunggu verifikasi',
                    'selesai' => 'Sudah diambil',
                    default => ucfirst($t->status_transaksi),
                },
            ]);
    }
}
