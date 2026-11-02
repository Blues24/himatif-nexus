---
trigger: "@backend"
keywords: ["controller", "service", "model", "migration", "route", "request", "validation", "database", "sql", "eloquent", "auth"]
paths:
  - "app/Http/Controllers/**"
  - "app/Http/Requests/**"
  - "app/Services/**"
  - "app/Models/**"
  - "app/Helpers/**"
  - "database/migrations/**"
  - "database/factories/**"
  - "database/seeders/**"
  - "routes/**"
---

# @backend — Backend Team Role

## Scope

Backend team owns semua controllers, services, models, migrations, routes, dan business logic.

### Team Boundaries

| Path | Backend | Frontend |
|---|---|---|
| `app/Http/Controllers/` | **Full Access** | Read Only |
| `app/Http/Requests/` | **Full Access** | No Access |
| `app/Services/` | **Full Access** | No Access |
| `app/Models/` | **Full Access** | No Access |
| `app/Http/Middleware/` | **Full Access** | Read Only |
| `database/migrations/` | **Full Access** | No Access |
| `routes/` | **Full Access** | Read Only |
| `config/` | **Full Access** | No Access |

## Conventions

### Controller Pattern

```php
<?php

namespace App\Http\Controllers\DanusSHop;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdukStoreRequest;
use App\Services\ProdukService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProdukController extends Controller
{
    public function __construct(private readonly ProdukService $service) {}

    public function index(Request $request): Response
    {
        $search = $request->query('q', '');
        return Inertia::render('Shop/Index', [
            'products' => fn () => filled($search)
                ? $this->service->search($search)->items()
                : $this->service->paginate()->items(),
            'filters' => ['q' => $search],
        ]);
    }

    public function show(int $id): Response
    {
        return Inertia::render('Shop/Show', [
            'product' => $this->service->findOrFail($id),
        ]);
    }

    public function store(ProdukStoreRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());
        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }
}
```

### Service Layer Pattern

```php
class ProdukService extends BaseService
{
    protected string $model = Produk::class;
    protected array $fillableColumns = [
        'nama_produk',
        'deskripsi_produk',
        'harga_produk',
        'stok_produk',
        'gambar_produk',
    ];

    public function search(string $query): LengthAwarePaginator
    {
        return $this->model::where('nama_produk', 'like', "%{$query}%")
            ->paginate(15);
    }
}
```

### Form Request Pattern

```php
class ProdukStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string', 'max:255'],
            'deskripsi_produk' => ['nullable', 'string'],
            'harga_produk' => ['required', 'numeric'],
            'stok_produk' => ['required', 'integer', 'min:0'],
            'gambar_produk' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
```

### Model Pattern

```php
class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produk_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_produk',
        'deskripsi_produk',
        'harga_produk',
        'stok_produk',
        'gambar_produk',
    ];

    protected function casts(): array
    {
        return [
            'harga_produk' => 'decimal:2',
        ];
    }
}
```

## Database Conventions

### Primary Keys

| Model | Primary Key | Type |
|---|---|---|
| `User` | `user_uuid` | UUID |
| `Produk` | `produk_id` | bigint |
| `Formatif` | `formatif_id` | bigint |
| `Kegiatan` | `kegiatan_id` | bigint |
| `Transaksi` | `transaksi_id` | bigint |
| `Inventaris` | `inventaris_id` | bigint |
| `PenyewaanAlat` | `id` | bigint |
| `Role` | `role_id` | bigint |

### Table Naming

- Use **singular** Indonesian names: `produk`, `kegiatan`, `transaksi`, `inventaris_alat`
- Exception: `penyewaan_alats` (Laravel pluralizer default)
- Models must define `$table` explicitly to avoid mismatch

### Foreign Keys

```php
// UUID foreign key
$table->uuid('user_id')->constrained('users', 'user_uuid');

// BigInt foreign key
$table->foreignId('role_id')->constrained('roles', 'role_id');
```

## Route Conventions

### RESTful Routes

```php
Route::controller(ProdukController::class)->group(function () {
    Route::get('/produk', 'index')->name('produk.index');
    Route::get('/produk/{id}', 'show')->name('produk.show');
    Route::middleware('auth')->group(function () {
        Route::get('/produk/create', 'create')->name('produk.create');
        Route::post('/produk', 'store')->name('produk.store');
        Route::get('/produk/{id}/edit', 'edit')->name('produk.edit');
        Route::patch('/produk/{id}', 'update')->name('produk.update');
        Route::delete('/produk/{id}', 'destroy')->name('produk.destroy');
    });
});
```

### Route Ordering

```php
// Literal routes BEFORE parameterized routes
Route::get('/produk/create', 'create');  // First
Route::get('/produk/{id}', 'show');       // Second (catches {id})
```

## Common Tasks

### Create New Controller

```php
php artisan make:controller Admin/UserController
```

### Create New Service

```php
class KegiatanService extends BaseService
{
    protected string $model = Kegiatan::class;
    protected array $fillableColumns = [
        'nama_kegiatan',
        'deskripsi_kegiatan',
        'tanggal_pelaksanaan',
        'status_kegiatan',
    ];
}
```

### Create New Model

```php
php artisan make:model Kategori
```

### Create New Migration

```php
php artisan make:migration create_kategoris_table
```

## Related Roles

- `@shop` — ProdukController, ProdukService
- `@infokom` — FormatifController, FormatifService
- `@portal` — InventarisController, PenyewaanAlatController
- `@admin` — UserController, UserService