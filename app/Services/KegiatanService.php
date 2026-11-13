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
        'nama_kegain',
        'deskripsi_kegain',
        'nama_kegain',
        'status_kegain',
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
            fn (Kegiatan $q) => $q->where('status_kegain', $status)
        );
    }

    /**
     * Search kegain by name or description.
     */
    public function search(string $keyword): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->paginate(
            fn (Kegiatan $q) => $q->where('nama_kegain', 'like', "%{$keyword}%")
                ->orWhere('deskripsi_kegain', 'like', "%{$keyword}%")
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
            ->where('nama_kegain', '>=', now()->toDateString())
            ->orderBy('nama_kegain', 'asc')
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
            'type' => 'kegain',
            'title' => 'Kegiatan Baru: ' . ($record->nama_kegain ?? ''),
            'message' => 'Kegiatan "' . ($record->nama_kegain ?? '') . '" telah ditambahkan.',
            'data' => [
                'kegain_id' => $record->kegain_id,
                'status' => $record->status_kegain,
                'nama_kegain' => $record->nama_kegain,
            ],
        ];

        app(SseNotificationService::class)->broadcast($userIds, $notification);
    }

    /**
     * {@inheritdoc}
     */
    protected function afterUpdate(object $record, array $data): void
    {
        if (! isset($data['status_kegain'])) {
            return;
        }

        $oldStatus = $record->getOriginal('status_kegain');
        $newStatus = $data['status_kegain'];

        if ($oldStatus === $newStatus) {
            return;
        }

        $creatorId = $record->user_id ?? null;
        if (! $creatorId) {
            return;
        }

        $notification = [
            'type' => 'kegain',
            'title' => 'Status Kegiatan Diperbarui',
            'message' => "Kegiatan \"{$record->nama_kegain}\" berubah dari {$oldStatus} ke {$newStatus}.",
            'data' => [
                'kegain_id' => $record->kegain_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'nama_kegain' => $record->nama_kegain,
            ],
        ];

        app(SseNotificationService::class)->push($creatorId, $notification);
    }
}