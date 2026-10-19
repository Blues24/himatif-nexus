<?php

namespace App\Services;

use App\Helpers\AutoSlugHelper;
use App\Models\Formatif;

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

    /**
     * Override create to auto-generate slug from judul.
     */
    public function create(array $data): object
    {
        if (! isset($data['slug'])) {
            $data['slug'] = AutoSlugHelper::generate(
                subject: $data['judul'],
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
            fn (Formatif $q) => $q->where('status', 'publish')
        );
    }

    /**
     * Search by title or content snippet.
     */
    public function search(string $keyword)
    {
        return $this->paginate(
            fn (Formatif $q) => $q->where('judul', 'like', "%{$keyword}%")
                ->orWhere('kategori', 'like', "%{$keyword}%")
        );
    }
}
