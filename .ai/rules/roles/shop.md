---
trigger: "@shop"
keywords: ["produk", "shop", "danus", "stok", "harga", "keranjang", "transaksi", "produk_id"]
paths:
  - "app/Http/Controllers/DanusSHop/**"
  - "app/Services/ProdukService.php"
  - "app/Models/Produk.php"
  - "app/Models/ShopDetail.php"
  - "resources/js/Pages/Shop/**"
---

# @shop — Danus Shop Role

## Scope

Danus Shop module mengelola katalog produk, pricing, dan transaksi penjualan.

### Files Owned

| File | Purpose |
|---|---|
| `app/Http/Controllers/DanusSHop/ProdukController.php` | Product CRUD controller |
| `app/Services/ProdukService.php` | Business logic untuk produk |
| `app/Models/Produk.php` | Product model |
| `app/Models/ShopDetail.php` | Transaction line items |
| `resources/js/Pages/Shop/Index.vue` | Product listing + search |
| `resources/js/Pages/Shop/Show.vue` | Product detail |
| `resources/js/Pages/Shop/Create.vue` | Add product form |
| `resources/js/Pages/Shop/Edit.vue` | Edit product form |

## Conventions

### Controller Pattern

```php
// ✅ Correct — thin controller, service delegation
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
}
```

### Service Layer

- Semua business logic di `ProdukService`
- Extend `BaseService` untuk CRUD dasar
- Override `afterCreate()`, `afterUpdate()` untuk side effects
- Method khusus: `search()`, `getLowStock()`, `adjustStock()`

### Validation

- `ProdukStoreRequest` untuk create
- `ProdukUpdateRequest` untuk update
- Rules: nama_produk (required, max:255), harga_produk (required, numeric), stok_produk (required, integer)

### File Upload

```php
// Image upload convention
if ($request->hasFile('gambar_produk')) {
    $data['gambar_produk'] = $request->file('gambar_produk')->store('produk', 'public');
    
    // Delete old image on update
    if ($existing->gambar_produk && Storage::disk('public')->exists($existing->gambar_produk)) {
        Storage::disk('public')->delete($existing->gambar_produk);
    }
}
```

### Pricing Format

```php
// Use CurrencyHelper for IDR formatting
use App\Helpers\CurrencyHelper;

'harga_formatted' => CurrencyHelper::format($product->harga_produk)
// Output: "Rp 150.000"
```

### Pagination

```php
// Always paginate in index()
$products = $this->service->paginate(15)->items();
```

### Search

```php
// Search by product name
$products = $this->service->search($query)->items();
```

## Database Schema

### `produk` Table

| Column | Type | Description |
|---|---|---|
| `produk_id` | bigint (PK) | Auto-increment primary key |
| `nama_produk` | string | Product name |
| `deskripsi_produk` | text | Product description |
| `harga_produk` | decimal(15,2) | Price in IDR |
| `stok_produk` | integer | Stock quantity (default: 0) |
| `gambar_produk` | string (nullable) | Image path in storage |
| `timestamps` | timestamp | created_at, updated_at |

### `shop_details` Table

| Column | Type | Description |
|---|---|---|
| `shop_detail_id` | bigint (PK) | Auto-increment |
| `transaksi_id` | uuid (FK) | Reference to transaksi |
| `produk_id` | bigint (FK) | Reference to produk |
| `quantity` | integer | Quantity purchased |
| `harga_satuan` | decimal(15,2) | Price at time of purchase |
| `timestamps` | timestamp | created_at, updated_at |

## Frontend Pages

### Shop/Index.vue

- Display product grid with search
- Show low stock warning for BPH/Danus role
- Deferred props for performance
- Filter by search query `q`

### Shop/Show.vue

- Product detail view
- Formatted price with `CurrencyHelper`
- Stock availability indicator

### Shop/Create.vue & Shop/Edit.vue

- Form with validation
- Image upload with preview
- Price input with IDR formatting

## Common Tasks

### Add New Product

1. Create validation in `ProdukStoreRequest`
2. Implement `store()` in `ProdukController`
3. Handle file upload
4. Call `$this->service->create($data)`
5. Redirect with success message

### Update Stock

```php
// In ProdukService
public function adjustStock(int $productId, int $quantity): void
{
    $product = $this->findOrFail($productId);
    $product->update(['stok_produk' => $product->stok_produk + $quantity]);
}
```

### Get Low Stock Products

```php
// In ProdukService
public function getLowStock(int $threshold = 10): Collection
{
    return $this->model::where('stok_produk', '<=', $threshold)
        ->orderBy('stok_produk')
        ->get();
}
```

## Related Roles

- `@portal` — Transaksi module uses ShopDetail
- `@admin` — BPH can view all products and stock levels
- `@frontend` — Vue components for product display