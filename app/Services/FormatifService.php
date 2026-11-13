<?php

namespace App\Services;

use App\Helpers\AutoSlugHelper;
use App\Models\Formatif;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FormatifService extends BaseService
{
    protected string $model = Formatif::class;

    protected array $fillableColumns = [
        'user_id',
        'judul',
        'slug',
        'kategori',
        'text_konten',
        'thumbnail_path',
        'status',
    ];

    protected array $withRelations = ['user'];

    /** @var array<string> Roles that receive notifications on formatif events. */
    protected array $notifyRoles = ['BPH', 'Infokom'];

    /**
     * Override create to auto-generate slug from judul.
     */
    public function create(array $data): object
    {
        if (! isset($data['slug'])) {
            $data['slug'] = AutoSlugHelper::generate(
                subject: $data['judul'] ?? '',
                table: 'formatif',
                slugColumn: 'slug'
            );
        }

        return parent::create($data);
    }

    /**
     * List published articles only.
     */
    public function published()
    {
        return $this->paginate(
            fn (\Illuminate\Database\Eloquent\Builder|Formatif $q) => $q->where('status', 'publish')
        );
    }

    /**
     * Search by title or content snippet.
     */
    public function search(string $keyword)
    {
        return $this->paginate(
            fn (Builder $q) => $q->where('judul', 'like', "%{$keyword}%")
                ->orWhere('kategori', 'like', "%{$keyword}%")
        );
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
            'type' => 'formatif',
            'title' => 'Artikel Baru: ' . ($record->judul ?? ''),
            'message' => "Artikel \"{$record->judul}\" telah dibuat.",
            'data' => [
                'formatif_id' => $record->formatif_id,
                'status' => $record->status,
                'slug' => $record->slug,
                'judul' => $record->judul,
            ],
        ];

        app(SseNotificationService::class)->broadcast($userIds, $notification);
    }

    /**
     * {@inheritdoc}
     */
    protected function afterUpdate(object $record, array $data): void
    {
        if (! isset($data['status'])) {
            return;
        }

        $oldStatus = $record->getOriginal('status');
        $newStatus = $data['status'];

        if ($oldStatus !== $newStatus) {
            // Notify followers when article is published.
            if ($newStatus === 'publish') {
                $userIds = $this->getRoleUserIds();
                if (empty($userIds)) {
                    return;
                }

                $notification = [
                    'type' => 'formatif',
                    'title' => 'Artikel Diterbitkan',
                    'message' => "Artikel \"{$record->judul}\" telah diterbitkan.",
                    'data' => [
                        'formatif_id' => $record->formatif_id,
                        'status' => $newStatus,
                        'slug' => $record->slug,
                        'judul' => $record->judul,
                    ],
                ];

                app(SseNotificationService::class)->broadcast($userIds, $notification);
            }
        }
    }
}