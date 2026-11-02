<?php

namespace App\Services;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Collection;

class KegiatanService extends BaseService
{
    protected string $model = Kegiatan::class;

    protected array $fillableColumns = [
        'nama_kegiatan',
        'deskripsi_kegiatan',
        'tanggal_pelaksanaan',
        'status_kegiatan',
    ];

    protected array $withRelations = [];

    /**
     * Filter kegiatan by status enum (rencana, berjalan, selesai).
     */
    public function byStatus(string $status)
    {
        return $this->paginate(
            fn (Kegiatan $q) => $q->where('status_kegiatan', $status)
        );
    }

    /**
     * Search kegiatan by name or description.
     */
    public function search(string $keyword)
    {
        return $this->paginate(
            fn (Kegiatan $q) => $q->where('nama_kegiatan', 'like', "%{$keyword}%")
                ->orWhere('deskripsi_kegiatan', 'like', "%{$keyword}%")
        );
    }

    /**
     * Get upcoming activities sorted by date.
     */
    public function upcoming(): Collection
    {
        return app(Kegiatan::class)->where('tanggal_pelaksanaan', '>=', now()->toDateString())
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->get();
    }

    protected function afterCreate(object $record, array $data): void
    {
        // TODO: Notify members via email/notification on creation
    }

    protected function afterUpdate(object $record, array $data): void
    {
        // TODO: Send update notification if status changed
        if (isset($data['status_kegiatan']) && $record->getOriginal('status_kegiatan') !== $data['status_kegiatan']) {
            // send status-change notification
        }
    }
}
