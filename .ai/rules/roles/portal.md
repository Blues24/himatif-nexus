---
trigger: "@portal"
keywords: ["inventaris", "peminjaman", "alat", "penyewaan", "pinjam", "kembali", "approve", "reject", "inventaris_id"]
paths:
  - "app/Http/Controllers/Portal/**"
  - "app/Services/InventarisService.php"
  - "app/Services/PenyewaanAlatService.php"
  - "app/Models/Inventaris.php"
  - "app/Models/PenyewaanAlat.php"
  - "resources/js/Pages/Inventory/**"
---

# @portal — Portal Role

## Scope

Portal module mengelola inventaris alat dan peminjaman/pengembalian peralatan organisasi.

### Files Owned

| File | Purpose |
|---|---|
| `app/Http/Controllers/Portal/InventarisController.php` | Inventory CRUD controller |
| `app/Http/Controllers/Portal/PenyewaanAlatController.php` | Equipment loan workflow controller |
| `app/Services/InventarisService.php` | Business logic untuk inventaris |
| `app/Services/PenyewaanAlatService.php` | Business logic untuk peminjaman |
| `app/Models/Inventaris.php` | Inventory model |
| `app/Models/PenyewaanAlat.php` | Loan request model |
| `resources/js/Pages/Inventory/Index.vue` | Inventory listing |
| `resources/js/Pages/Inventory/Create.vue` | Add inventory form |
| `resources/js/Pages/Inventory/Edit.vue` | Edit inventory form |
| `resources/js/Pages/Inventory/Loans.vue` | Loan requests listing |
| `resources/js/Pages/Inventory/LoanCreate.vue` | Create loan request form |

## Conventions

### Controller Pattern

```php
// InventarisController
class InventarisController extends Controller
{
    public function __construct(private readonly InventarisService $service) {}

    public function index(): Response
    {
        return Inertia::render('Inventory/Index', [
            'baik' => fn () => $this->service->byCondition('baik')->items(),
            'rusak' => fn () => $this->service->byCondition('rusak')->items(),
            'hilang' => fn () => $this->service->byCondition('hilang')->items(),
        ]);
    }
}

// PenyewaanAlatController - workflow based
class PenyewaanAlatController extends Controller
{
    public function approve(int $id): RedirectResponse
    {
        $this->service->handleRequest($id, 'approve');
        return back()->with('success', 'Peminjaman disetujui.');
    }
}
```

### Validation

- `InventarisStoreRequest` untuk create
- `InventarisUpdateRequest` untuk update
- `PenyewaanAlatStoreRequest` untuk loan request

### Peminjaman Workflow

```
Status Flow:
pending → approve → returned
         → reject (end)

Actions:
- submitRequest()  : create new loan (status: pending)
- handleRequest()  : approve or reject
- markReturned()   : mark as returned
```

### Service Layer

- `InventarisService` — CRUD inventaris + filter by condition
- `PenyewaanAlatService` — Loan workflow (submit, approve, reject, return)

## Database Schema

### `inventaris_alat` Table

| Column | Type | Description |
|---|---|---|
| `inventaris_id` | bigint (PK) | Auto-increment |
| `nama_barang` | string | Item name |
| `stok` | integer (default: 0) | Stock quantity |
| `kondisi` | string (default: 'baik') | 'baik', 'rusak', 'hilang' |
| `timestamps` | timestamp | created_at, updated_at |

### `penyewaan_alats` Table

| Column | Type | Description |
|---|---|---|
| `id` | bigint (PK) | Auto-increment |
| `inventaris_alat_id` | bigint (FK) | Reference to inventaris_alat |
| `user_id` | uuid (FK) | Borrower reference to users |
| `jumlah_pinjam` | integer | Quantity borrowed |
| `tanggal_pinjam` | date | Borrow date |
| `tanggal_kembali` | date (nullable) | Return date |
| `status` | string (default: 'pending') | 'pending', 'approve', 'reject', 'returned' |
| `timestamps` | timestamp | created_at, updated_at |

## Common Tasks

### Create Inventory Item

```php
// In InventarisController@store
$this->service->create($request->validated());
```

### Submit Loan Request

```php
// In PenyewaanAlatController@store
$data = $request->validated();
$data['user_id'] = $request->user()->user_uuid;
$data['tanggal_pinjam'] = now()->toDateString();
$this->service->submitRequest($data);
```

### Approve/Reject Loan

```php
// In PenyewaanAlatService
public function handleRequest(int $id, string $action): void
{
    $loan = $this->findOrFail($id);
    if ($action === 'approve') {
        $loan->update(['status' => 'approve']);
        // Decrease stock
        $inventory = $loan->inventarisAlat;
        $inventory->update(['stok' => $inventory->stok - $loan->jumlah_pinjam]);
    }
}
```

### Return Equipment

```php
// In PenyewaanAlatService
public function markReturned(int $id): void
{
    $loan = $this->findOrFail($id);
    $loan->update([
        'status' => 'returned',
        'tanggal_kembali' => now()->toDateString(),
    ]);
    // Increase stock
    $inventory = $loan->inventarisAlat;
    $inventory->update(['stok' => $inventory->stok + $loan->jumlah_pinjam]);
}
```

## Frontend Pages

### Inventory/Index.vue

- Display items grouped by condition (baik, rusak, hilang)
- Filter tabs or sections per condition
- Deferred props for performance

### Inventory/Loans.vue

- List all loan requests
- Show status badges (pending, approve, reject, returned)
- Action buttons for approve/reject/return

## Related Roles

- `@shop` — Transaksi module might integrate with inventory
- `@admin` — BPH can approve/reject all loans
- `@frontend` — Vue components for inventory display