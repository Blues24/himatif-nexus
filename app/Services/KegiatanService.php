<?php

namespace App\Services;

use App\Models\Kegiatan;
use App\Models\User;
use App\Models\Role;
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

    /** @var array<string> Roles that receive notifications on new kegain. */
    protected array $notifyRoles = ['BPH', 'Infokom'];

    /**
     * Filter kegain by status enum (rencana, berjalan, selesai).
     */
    public function byStatus(string $status): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->paginate(
            fn (\Illuminate\Database\Eloquent\Builder|Kegiatan $q) => $q->where('status_kegiatan', $status)
        );
    }

    /**
     * Search kegain by name or description.
     */
    public function search(string $keyword): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->paginate(
            fn (\Illuminate\Database\Eloquent\Builder|Kegiatan $q) => $q->where('nama_kegiatan', 'like', "%{$keyword}%")
                ->orWhere('deskripsi_kegiatan', 'like', "%{$keyword}%")
        );
    }

    /**
     * Get upcoming kegain sorted by date.
     *
     * @return Collection<int, Kegiatan>
     */
    public function upcoming(): Collection
    {
        return $this->model::query()
            ->where('tanggal_pelaksanaan', '>=', now()->toDateString())
            ->orderBy('tanggal_pelaksanaan', 'asc')
            ->get();
    }

    /**
     * Get user IDs for the notification roles.
     *
     * @return array<int|string>
     */
    protected function getRoleUserIds(): array
    {
        return User::whereHas('role', function ($q) {
            $q->where('nama_role', $this->notifyRoles[0])
                ->orWhere('nama_role', $this->notifyRoles[1]);
        })->pluck('user_uuid')->all();
    }

    /**
     * {@inheritdoc}
     */
    protected function afterCreate(object $record, array $data): void
    {
        $userIds = $this->getRoleUserIds();
        if (empty($userIds)) {
            return;
        }

        $notification = [
            'type' => 'kegiatan',
            'title' => 'Kegiatan Baru: ' . ($record->nama_kegiatan ?? ''),
            'message' => 'Kegiatan "' . ($record->nama_kegiatan ?? '') . '" telah ditambahkan.',
            'data' => [
                'kegiatan_id' => $record->kegiatan_id,
                'status' => $record->status_kegiatan,
                'nama_kegiatan' => $record->nama_kegiatan,
            ],
        ];

        app(SseNotificationService::class)->broadcast($userIds, $notification);
    }

    /**
     * {@inheritdoc}
     */
    protected function afterUpdate(object $record, array $data): void
    {
        if (! isset($data['status_kegiatan'])) {
            return;
        }

        $oldStatus = $record->getOriginal('status_kegiatan');
        $newStatus = $data['status_kegiatan'];

        if ($oldStatus === $newStatus) {
            return;
        }

        $creatorId = $record->user_id ?? null;
        if (! $creatorId) {
            return;
        }

        $notification = [
            'type' => 'kegiatan',
            'title' => 'Status Kegiatan Diperbarui',
            'message' => "Kegiatan \"{$record->nama_kegiatan}\" berubah dari {$oldStatus} ke {$newStatus}.",
            'data' => [
                'kegiatan_id' => $record->kegiatan_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'nama_kegiatan' => $record->nama_kegiatan,
            ],
        ];

        app(SseNotificationService::class)->push($creatorId, $notification);
    }
}