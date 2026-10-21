---
trigger: "@infokom"
keywords: ["formatif", "blog", "kegiatan", "artikel", "puck", "artikel_id", "kegiatan_id"]
paths:
  - "app/Http/Controllers/Infokom/**"
  - "app/Services/FormatifService.php"
  - "app/Services/KegiatanService.php"
  - "app/Models/Formatif.php"
  - "app/Models/Kegiatan.php"
  - "resources/js/Pages/Blog/**"
  - "resources/js/Pages/Kegiatan/**"
---

# @infokom — Infokom Role

## Scope

Infokom (Informasi & Komunikasi) mengelola konten artikel/blog dan kegiatan organisasi.

### Files Owned

| File | Purpose |
|---|---|
| `app/Http/Controllers/Infokom/FormatifController.php` | Blog/article CRUD controller |
| `app/Http/Controllers/Infokom/KegiatanController.php` | ⚠️ Stub — needs implementation |
| `app/Services/FormatifService.php` | Business logic untuk artikel |
| `app/Services/KegiatanService.php` | Business logic untuk kegiatan |
| `app/Models/Formatif.php` | Article model |
| `app/Models/Kegiatan.php` | Activity model |
| `resources/js/Pages/Blog/Index.vue` | Article listing |
| `resources/js/Pages/Blog/Show.vue` | Article detail |
| `resources/js/Pages/Blog/Create.vue` | Create article form |
| `resources/js/Pages/Blog/Edit.vue` | Edit article form |
| `resources/js/Builder/puckConfig.js` | Puck page builder config (planned) |
| `resources/js/Builder/Components/**` | Puck block components (planned) |

## Conventions

### Controller Pattern

```php
class FormatifController extends Controller
{
    public function __construct(private readonly FormatifService $service) {}

    public function index(Request $request): Response
    {
        $search = $request->query('q', '');
        return Inertia::render('Blog/Index', [
            'formatifs' => fn () => filled($search)
                ? $this->service->search($search)->items()
                : $this->service->paginate()->items(),
            'filters' => ['q' => $search],
        ]);
    }

    public function published(): Response
    {
        return Inertia::render('Blog/Show', [
            'formatifs' => fn () => $this->service->published()->items(),
        ]);
    }
}
```

### Validation

- `FormatifStoreRequest` untuk create
- `FormatifUpdateRequest` untuk update
- Rules: judul (required, max:255), slug (required, unique), kategori (required), status (required, in:draft,published)

### Status Management

```php
// Formatif status
'draft'      // Artikel masih dalam proses penulisan
'published'  // Artikel sudah dipublikasikan
```

### Service Layer

- Semua business logic di `FormatifService`
- Extend `BaseService` untuk CRUD dasar
- Method khusus: `search()`, `published()`, `byCategory()`

### Puck Integration (Planned)

```javascript
// resources/js/Builder/puckConfig.js
export default {
  blocks: {
    BannerBlock,
    CardLomba,
    GridBlock,
  },
  controls: {
    SidebarColorControl,
  },
}
```

## Database Schema

### `formatif` Table

| Column | Type | Description |
|---|---|---|
| `formatif_id` | bigint (PK) | Auto-increment |
| `user_id` | uuid (FK) | Author reference to users |
| `judul` | string | Article title |
| `slug` | string (unique) | URL slug |
| `kategori` | string | Article category |
| `text_konten` | jsonb | Puck layout JSON tree (PostgreSQL jsonb, NOT LONGTEXT) |
| `thumbnail_path` | string (nullable) | Thumbnail image |
| `status` | string (default: 'draft') | 'draft' or 'published' |
| `timestamps` | timestamp | created_at, updated_at |

### `kegiatan` Table

| Column | Type | Description |
|---|---|---|
| `kegiatan_id` | bigint (PK) | Auto-increment |
| `nama_kegiatan` | string | Activity name |
| `deskripsi_kegiatan` | text | Activity description |
| `tanggal_pelaksanaan` | date | Implementation date |
| `status_kegiatan` | string (default: 'rencana') | 'rencana', 'berlangsung', 'selesai' |
| `timestamps` | timestamp | created_at, updated_at |

## Common Tasks

### Create New Article

1. Validate with `FormatifStoreRequest`
2. Handle thumbnail upload
3. Call `$this->service->create($data)`
4. Redirect with success message

### Search Articles

```php
// In FormatifService
public function search(string $query): LengthAwarePaginator
{
    return $this->model::where('judul', 'like', "%{$query}%")
        ->orWhere('kategori', 'like', "%{$query}%")
        ->paginate(15);
}
```

### Get Published Articles

```php
// In FormatifService
public function published(): LengthAwarePaginator
{
    return $this->model::where('status', 'published')->paginate(15);
}
```

### Puck Block Component

```vue
<!-- BannerBlock.vue -->
<template>
  <div class="banner" :style="{ background: props.backgroundColor }">
    <h2>{{ props.title }}</h2>
    <p>{{ props.description }}</p>
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
  description: String,
  backgroundColor: String,
})
</script>
```

## Related Roles

- `@shop` — Artikel bisa mention produk
- `@portal` — Kegiatan bisa mention peralatan
- `@admin` — BPH approve/publish artikel
- `@frontend` — Vue components untuk blog