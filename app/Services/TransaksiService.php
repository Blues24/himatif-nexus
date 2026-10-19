<?php

namespace App\Services;

use App\Models\Transaksi;

class TransaksiService extends BaseService
{
    protected string $model = Transaksi::class;

    protected array $fillableColumns = [
        'user_id',
        'total_bayar',
        'status_transaksi',
    ];

    protected array $withRelations = ['user', 'productLines'];

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
    public function byStatus(string $status)
    {
        return $this->paginate(
            fn (Transaksi $q) => $q->where('status_transaksi', $status)
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
}
