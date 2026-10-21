# System Architecture — HIMATIF Nexus (`himatif-nexus`)

> **Languages / Bahasa:** [English (en-US)](./ARCHITECTURE.md) · [Bahasa Indonesia (id-ID)](./ARCHITECTURE.id-ID.md)

## 1. Executive Overview & Design Pattern

**HIMATIF Nexus** is built as a **Monolith-SPA (Single Page Application)**, pairing **Laravel (PHP 8.5+)** on the backend with **Vue 3 + Inertia.js** on the frontend.

* **Backend:** Laravel owns authentication, the RBAC system, the Eloquent ORM, business validation, and database management.
* **Frontend:** Vue 3 + Tailwind CSS render a reactive user interface, without the need for a separate REST API.
* **Database:** **PostgreSQL** as the relational database management system (see [§3](#3-database-engine--postgresql)).
* **Content Builder (Infokom):** **Puck (`@measured/puck`)** combined with **`vue3-colorpicker`** for a visual drag-and-drop page builder plus color-scheme customization (Brand Presets & a custom Hex/RGB color wheel).

---

## 2. Technical Stack Specification

Versions below reflect what is actually declared in `composer.json` and `package.json`. Items marked **(planned)** are target stack that is **not installed yet**.

| Layer | Technology / Package | Version | Status | Function |
| :--- | :--- | :--- | :--- | :--- |
| **Backend Framework** | `laravel/framework` | ^13.35 (PHP ^8.5) | Installed | Core application, routing, controllers, ORM |
| **Frontend Framework** | `vue` (Composition API, `<script setup>`) | ^3.4 | Installed | Reactive user interface (UI) |
| **Monolith-SPA Glue** | `inertiajs/inertia-laravel` + `@inertiajs/vue3` | ^2.0 | Installed | Data/props passing without a manual REST API |
| **Auth Scaffolding** | `laravel/breeze` (+ `laravel/sanctum`, `tightenco/ziggy`) | ^2.4 / ^4.0 / ^2.0 | Installed | Login, registration, password & profile flows; named routes in JS |
| **Page & Blog Builder** | `@measured/puck` | — | **(planned)** | Visual drag-and-drop block editor |
| **Color Picker** | `vue3-colorpicker` | — | **(planned)** | Color wheel & hex input for custom styles |
| **Styling & UI** | `tailwindcss` + `@tailwindcss/forms` | ^3.2 (v3 directives in `resources/css/app.css`) | Installed | Utility-first CSS styling |
| **Build Tool** | `vite` + `laravel-vite-plugin` | ^8.0 / ^3.1 | Installed | Asset bundling & HMR |
| **RBAC Engine** | **Custom** (tabel `roles` + relasi `User::role()`) | — | **Implemented** | Role & permission management via `hasRole()`, `isBph()` methods |
| **Database Engine** | **PostgreSQL** | 18.x / 17.x | **Target** | Relational database engine |

---

## 3. Database Engine — PostgreSQL

The project uses **PostgreSQL** instead of the Laravel default (SQLite). `config/database.php` already ships a fully defined `pgsql` connection block (port `5432`, `search_path => 'public'`, `sslmode` via `DB_SSLMODE`).

### 3.1 Connection Configuration

```env
# .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=himatif_nexus
DB_USERNAME=himatif
DB_PASSWORD=<secret>
DB_SSLMODE=prefer
```

Required PHP extensions: `pdo_pgsql` (and `pgsql` for direct PDO access).

### 3.2 Dialect Differences That Affect This Project

| Concern | MySQL / MariaDB / SQLite | PostgreSQL — What to Use Instead |
| :--- | :--- | :--- |
| Long content body (`text_konten`) | `LONGTEXT` | **`jsonb`** (preferred) or `text` — **PostgreSQL has no `LONGTEXT`** |
| Puck layout tree search | `LIKE` scans | `jsonb` + GIN index: `$table->jsonb('text_konten')->default('{}')` |
| Identifiers | Case-sensitive mix | Unquoted identifiers fold to **lowercase** — use `snake_case` columns/tables, never camelCase |
| Enum columns | Native `enum()` | `$table->string('status')->check(DB::raw(...))` or a native `CREATE TYPE ... AS ENUM` via `DB::statement` |
| Boolean / JSON | `tinyint(1)` / `json` text blob | Native `boolean`, native `jsonb` with real containment operators (`@>`, `?`) |
| Sequences | `AUTO_INCREMENT` | `$table->id()` maps to `bigint generated ... as identity` — no special handling needed |
| Index name length | 64 chars, silently truncated | **63-byte limit, raises an error** — keep composite index names short |

### 3.3 Migration Status

All framework tables are present and domain migrations have full column definitions:

| Migration File | Status | Description |
| :--- | :--- | :--- |
| `0001_01_01_000001_create_cache_table.php` | ✅ Present | Cache table with `key`, `value`, `expiration` |
| `0001_01_01_000002_create_jobs_table.php` | ✅ Present | Jobs, batches, and failed_jobs tables |
| `2026_10_19_124812_create_sessions_table.php` | ✅ Present | Sessions table (empty migration — `SESSION_DRIVER=database` ready) |
| `2026_10_13_010000_create_roles_table.php` | ✅ Present | RBAC roles with `role_id`, `nama_role` |
| `2026_10_13_010100_create_users_table.php` | ✅ Present | Users with `user_uuid` (primary), `role_id`, `nama`, `email`, `password` |
| `2026_10_13_010200_create_kegiatan_table.php` | ✅ Present | Activities with full columns |
| `2026_10_13_010300_create_produk_table.php` | ✅ Present | Products with full columns |
| `2026_10_13_010400_create_inventaris_alat_table.php` | ✅ Present | Inventory with `nama_barang`, `stok`, `kondisi` |
| `2026_10_13_010500_create_transaksi_table.php` | ✅ Present | Transactions with `user_id`, `total_bayar`, `status_transaksi` |
| `2026_10_13_010600_create_formatif_table.php` | ✅ Present | Blog/articles with `judul`, `slug`, `text_konten`, `thumbnail_path`, `status` |
| `2026_10_13_010800_create_penyewaan_table.php` | ✅ Present | Equipment loans with foreign keys |

> **Note:** `database/database.sqlite` is a leftover dev artifact (empty file). Keep `DB_CONNECTION=pgsql` in `.env` to ensure PostgreSQL is always used.

---

## 4. Database Schema (Entity Overview)

Tables as created by the migrations in `database/migrations/`. The *Purpose* column is the design intent; the *Columns* column is the verified reality.

| Table | Model | Purpose | Columns |
| :--- | :--- | :--- | :--- |
| `users` | `User` | Accounts for all four teams | `user_uuid` (PK, UUID), `role_id` (FK), `nama`, `email`, `password`, `email_verified_at`, `remember_token`, `timestamps` |
| `roles` | `Role` | RBAC roles (BPH / Infokom / Portal / Danus Shop) | `role_id` (PK), `nama_role` |
| `formatif` | `Formatif` | Formatif articles & Puck content | `formatif_id` (PK), `user_id` (FK), `judul`, `slug`, `kategori`, `text_konten` (jsonb), `thumbnail_path`, `status`, `timestamps` |
| `kegiatan` | `Kegiatan` | Organizational activities/events | `kegiatan_id` (PK), `nama_kegiatan`, `deskripsi_kegiatan`, `tanggal_pelaksanaan`, `status_kegiatan`, `timestamps` |
| `transaksi` | `Transaksi` | Transactions across modules | `transaksi_id` (PK), `user_id` (FK, UUID), `total_bayar`, `status_transaksi`, `timestamps` |
| `produk` | `Produk` | Danus Shop product catalog | `produk_id` (PK), `nama_produk`, `deskripsi_produk`, `harga_produk`, `stok_produk`, `gambar_produk`, `timestamps` |
| `shop_details` | `ShopDetail` | Line items for transactions | `shop_detail_id` (PK), `transaksi_id` (FK), `produk_id` (FK), `quantity`, `harga_satuan`, `timestamps` |
| `inventaris_alat` | `Inventaris` | HM asset/equipment inventory | `inventaris_id` (PK), `nama_barang`, `stok`, `kondisi`, `timestamps` |
| `penyewaan_alats` | `PenyewaanAlat` | Equipment loan requests | `id` (PK), `inventaris_alat_id` (FK), `user_id` (FK, UUID), `jumlah_pinjam`, `tanggal_pinjam`, `tanggal_kembali`, `status`, `timestamps` |

> **Naming note:** Table names are **singular** in Indonesian (`kegiatan`, `transaksi`, `produk`, `inventaris_alat`) except `penyewaan_alats` which uses Laravel's default pluralization. Models explicitly define `$table` to ensure correct mapping.

---

## 5. Visual Page Builder Architecture (Puck Integration)

1. **Developer Config:** Register Vue template components (`BannerBlock`, `CardLomba`, `GridBlock`) and their *props* schema in `resources/js/Builder/puckConfig.js` *(directory not created yet)*.
2. **Infokom Editor Mode:**
   * Drag-and-drop components onto the canvas.
   * Configure text, layout options, and color scheme via the *sidebar control*.
   * Pick colors from the **HIMATIF Brand Presets** or the **custom color picker wheel (`vue3-colorpicker`)**.
3. **Storage Engine:** The resulting *layout* is stored as a clean JSON tree in the `text_konten` column of the `formatif` table — **`jsonb`** under PostgreSQL (formerly documented as `LONGTEXT`, which PostgreSQL does not support).
4. **Public Render Mode:** The `Blog/Show.vue` page reads the JSON and loops over components with a Vue dynamic component: `<component :is="block.component" v-bind="block.props" />`.

---

## 6. Team Folder Ownership & Access Boundary Matrix

To avoid *merge conflicts* during team collaboration:

| Folder / File Path | Backend Team | Frontend Team | Access Description & Boundaries |
| :--- | :--- | :--- | :--- |
| `app/Http/Controllers/` | **Full Access** | Read Only | Backend owns business logic & renders the Inertia views |
| `app/Http/Requests/` | **Full Access** | No Access | Backend owns server-side validation request classes |
| `app/Models/` | **Full Access** | No Access | Backend owns the Eloquent ORM & database relations |
| `app/Http/Middleware/` | **Full Access** | Read Only | Backend owns the RBAC & Inertia share middleware |
| `database/migrations/` | **Full Access** | No Access | Backend owns the **PostgreSQL** DDL schema |
| `database/factories/` & `database/seeders/` | **Full Access** | Read Only | Backend prepares fake & seed data |
| `routes/` | **Full Access** | Read Only | Backend registers route endpoints & RBAC middleware |
| `resources/js/Pages/` | Read Only | **Full Access** | Frontend composes per-module Vue page screens |
| `resources/js/Components/` | Read Only | **Full Access** | Frontend builds re-usable UI (Navbar, Modal, Table) |
| `resources/js/Layouts/` | Read Only | **Full Access** | Frontend owns page layout shells |
| `resources/js/Builder/` | Read Only | **Full Access** | Frontend owns the Puck page builder + color picker *(planned)* |
| `resources/css/` | Read Only | **Full Access** | Frontend owns Tailwind CSS styling |
| `resources/views/app.blade.php` | **Full Access** | Read Only | Backend owns the Inertia root template |
| `config/` | **Full Access** | No Access | Backend owns system config, database connections & RBAC |

> Adjusted from the previous revision: the Breeze-generated frontend-owned folders (`resources/js/Layouts/`, `resources/js/Pages/Auth`, `resources/js/Pages/Profile`) are already committed by the scaffold, so `resources/js/Components/` and `resources/css/` changed from *No Access* to *Read Only* for the backend — the backend must be able to patch scaffold code it generated.

---

## 7. SOLID Principles & Layered Architecture

Controllers must stay thin — they are HTTP gateways, not business logic engines. The application enforces five SOLID principles at the architectural level.

### 7.1 Single Responsibility Principle (SRP)

| Layer | Sole Responsibility | Example |
| :--- | :--- | :--- |
| **Controller** | Validate input → call service → return response | `ProdukController@store` receives request, calls `ProdukService::create`, redirects |
| **Model** | Data definition + relationships | `Produk::$fillable`, `User::role()` |
| **Service** | Business rules & cross-model operations | `ProdukService::adjustStock()`, `PenyewaanAlatService::handleRequest()` |
| **Form Request** | Validation only | `ProfileUpdateRequest`, `ProdukStoreRequest`, `FormatifUpdateRequest` |
| **Helper** | Pure, stateless utilities | `CurrencyHelper::format()`, `AutoSlugHelper::generate()` |

```text
Client → Controller → Service → Model ← Helper
         (HTTP)       (rules)    (data)   (utilities)
```

### 7.2 Open/Closed Principle (OCP)

New features extend behavior without modifying existing code:

- **Services** inherit from `BaseService` and override hooks (`afterCreate`, `afterUpdate`) instead of rewriting CRUD
- **Event subscribers** add side-effects without touching controllers
- **Middleware pipelines** compose new gates without editing route definitions

### 7.3 Liskov Substitution Principle (LSP)

All services implement a common interface implicitly through `BaseService`. Any concrete service can be swapped for another with identical caller signatures:

```php
// Caller never knows if ProdukService or MockProdukService is used
public function __construct(public ProdukService $produkService) {}
public function index(): Response { /* works with any BaseService subclass */ }
```

### 7.4 Interface Segregation Principle (ISP)

Small, focused responsibilities per class:

- Controllers do **not** inject massive multi-module services — each gets exactly one domain service
- Models define **only** the relations they actually own (no god-model anti-pattern)
- Form Requests validate **only** their specific form — no shared validation classes

### 7.5 Dependency Inversion Principle (DIP)

High-level modules depend on abstractions, not concretions:

```php
class ProdukController extends Controller
{
    // Depends on ProductService abstraction (implicitly via BaseService contract)
    public function __construct(private readonly ProdukService $service) {}
}
```

This allows swapping implementations (e.g., mocking for tests, different DB drivers) without touching controller code.

### 7.6 Directory Layout

```text
app/
├── Http/
│   ├── Controllers/           # Thin HTTP handlers — just gateways
│   │   ├── Admin/             # BPH management (UserController: stub)
│   │   ├── Auth/              # Breeze auth (8 controllers)
│   │   ├── DanusSHop/         # Danus Shop (ProdukController: implemented)
│   │   ├── Infokom/           # Content (FormatifController: implemented, KegiatanController: stub)
│   │   └── Portal/            # Asset management (InventarisController: implemented, PenyewaanAlatController: implemented)
│   ├── Middleware/
│   │   └── HandleInertiaRequests.php   # Inertia shared props
│   └── Requests/              # Form Request validation classes
├── Services/                  # Business logic layer (SRP, OCP)
│   ├── BaseService.php        # Shared CRUD skeleton
│   ├── ProdukService.php      # Danus Shop domain rules
│   ├── KegiatanService.php    # Activity management
│   ├── FormatifService.php    # Content publishing
│   ├── InventarisService.php  # Inventory tracking
│   ├── PenyewaanAlatService.php  # Rental workflow
│   ├── TransaksiService.php   # Transaction recording (not yet used)
│   └── UserService.php        # User management & RBAC assignment
├── Helpers/                   # Pure utilities — no framework coupling
│   ├── CurrencyHelper.php     # IDR formatting & parsing
│   └── AutoSlugHelper.php     # Unique slug generation
├── Models/                    # Eloquent entities — data + relations
│   ├── User.php               # Accounts, RBAC via role() relation
│   ├── Role.php               # Role definitions
│   ├── Formatif.php           # Blog/articles
│   ├── Kegiatan.php           # Activities/events
│   ├── Transaksi.php          # Transactions
│   ├── Produk.php             # Products
│   ├── ShopDetail.php         # Transaction line items
│   ├── Inventaris.php         # Inventory
│   └── PenyewaanAlat.php      # Equipment loans
└── Providers/
    └── AppServiceProvider.php
```

### 7.7 Controller Responsibilities

| Method | What It Does | Example |
| :--- | :--- | :--- |
| `index()` | Calls `$service->paginate($filter)` → returns Inertia response | `ProdukController::index()` with search |
| `create()` | Returns Inertia render for create form | `ProdukController::create()` |
| `store(Request $request)` | Validates via Form Request → `$service->create($validated)` → redirects | `ProdukController::store()` |
| `show($id)` | Calls `$service->findOrFail($id)` → returns Inertia response | `ProdukController::show()` |
| `edit($id)` | Calls `$service->findOrFail($id)` → returns Inertia render for edit form | `ProdukController::edit()` |
| `update(Request $request, $id)` | Validates → `$service->update($id, $validated)` → redirects | `ProdukController::update()` |
| `destroy($id)` | Calls `$service->delete($id)` → redirects back | `ProdukController::destroy()` |

**What NOT to put in controllers:** stock checks, price calculations, relationship traversals, notifications, audit logging, complex conditional queries — all go to Services.

### 7.8 Implemented vs Stub Controllers

| Controller | Status | Notes |
| :--- | :--- | :--- |
| `ProdukController` | ✅ Implemented | Full CRUD with search, file upload |
| `FormatifController` | ✅ Implemented | Full CRUD with `published()` method, file upload |
| `InventarisController` | ✅ Implemented | Full CRUD with condition-based filtering |
| `PenyewaanAlatController` | ✅ Implemented | Loan request workflow with approve/reject/return |
| `KegiatanController` | ⚠️ Stub | Empty — needs implementation |
| `TransaksiController` | ⚠️ Stub | Empty — needs implementation |
| `Admin\UserController` | ⚠️ Stub | Empty — needs implementation for BPH user management |

---

## 8. RBAC (Role-Based Access Control)

### 8.1 Implementation

RBAC is implemented **without Spatie** using a custom system:

- **Table `roles`**: Stores role definitions (`role_id`, `nama_role`)
- **Table `users`**: Has `role_id` foreign key linking to `roles`
- **Model `User`**: Provides `role()`, `hasRole()`, `isBph()` methods

```php
// User.php
public function role(): BelongsTo
{
    return $this->belongsTo(Role::class, 'role_id', 'role_id');
}

public function hasRole(string $roleName): bool
{
    return $this->role?->nama_role === $roleName;
}

public function isBph(): bool
{
    return $this->hasRole('BPH');
}
```

### 8.2 Available Roles

| Role | Description | Access Level |
| :--- | :--- | :--- |
| `BPH` | Badan Pengurus Harian | Full access to all modules |
| `Infokom` | Informasi & Komunikasi | Content management (Formatif, Kegiatan) |
| `Portal` | Portal Management | Inventory & equipment loans |
| `Danus` | Dana Usaha | Shop & product management |

### 8.3 Authorization Strategy (Planned)

The following authorization layers are **not yet implemented**:

- **Middleware**: Role-based route guards (e.g., `->middleware('role:BPH')`)
- **Policies**: Model-level authorization (e.g., `ProdukPolicy::update()`)
- **Gates**: Simple capability checks (e.g., `Gate::define('manage-shop', ...)`)

---

## 9. Best Practices & Guidelines

### 9.1 Service Layer Usage

**When to use Service:**
- Business logic that spans multiple models
- Complex validation beyond Form Request rules
- External API integrations
- Transaction-wrapped operations
- Notification triggers

**When NOT to use Service:**
- Simple CRUD with no business rules (use `BaseService` directly)
- Pure data transformation (use Model accessors/mutators)
- Framework-level operations (use Laravel facades)

### 9.2 Form Request Validation

All user input must be validated through Form Request classes:

```php
// ✅ Correct
public function store(ProdukStoreRequest $request): RedirectResponse
{
    $data = $request->validated();
    $this->service->create($data);
}

// ❌ Wrong — don't validate in controller
public function store(Request $request): RedirectResponse
{
    $data = $request->validate(['name' => 'required']);
}
```

### 9.3 Deferred Props for Performance

Use deferred props (`fn () =>`) for expensive queries in Inertia responses:

```php
// ✅ Correct — lazy evaluation
return Inertia::render('Shop/Index', [
    'products' => fn () => $this->service->paginate()->items(),
    'lowStock' => fn () => $this->service->getLowStock()->map(...),
]);

// ❌ Wrong — eager evaluation (always runs)
return Inertia::render('Shop/Index', [
    'products' => $this->service->paginate()->items(),
]);
```

### 9.4 Transaction Safety

All `create()` and `update()` operations in `BaseService` are wrapped in transactions:

```php
// BaseService.php
public function create(array $data): object
{
    return DB::transaction(function () use ($data) {
        $record = app($this->model)->create(array_only($data, $this->fillableColumns));
        $this->afterCreate($record, $data);
        return $record;
    });
}
```

### 9.5 Error Handling

| Scenario | Handling |
| :--- | :--- |
| Validation errors | Inertia handles automatically via Form Request |
| Not found | `findOrFail()` throws `ModelNotFoundException` → 404 |
| Unauthorized | **Not yet implemented** — needs Policies/Gates |
| Server errors | Global exception handler (`app/Exceptions/Handler.php`) |

### 9.6 Testing Strategy

| Test Type | Location | Purpose |
| :--- | :--- | :--- |
| Feature Tests | `tests/Feature/` | HTTP-level tests for routes, controllers, auth flows |
| Unit Tests | `tests/Unit/` | Isolated logic tests (rarely needed with Service Layer) |

**Coverage targets (planned):**
- All controllers: minimum 80% coverage
- All services: minimum 90% coverage
- Critical paths (auth, payments): 100% coverage

---

## 10. Known Issues & Technical Debt

| Issue | Priority | Status |
| :--- | :--- | :--- |
| RBAC middleware not implemented | 🔴 High | **Planned** |
| No Policies for model authorization | 🔴 High | **Planned** |
| `KegiatanController`, `TransaksiController`, `UserController` are stubs | 🟡 Medium | **Needs implementation** |
| `TransaksiService` exists but not used | 🟡 Medium | **Needs integration** |
| No API Resources (future mobile/webhook support) | 🟢 Low | **Planned** |
| No caching strategy documented | 🟢 Low | **Planned** |

---

## 11. Directory Structure (Actual)

```text
himatif-nexus/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php              # Base controller (abstract)
│   │   │   ├── ProfileController.php       # Root-level (Breeze profile flow)
│   │   │   ├── Admin/
│   │   │   │   └── UserController.php      # ⚠️ Stub
│   │   │   ├── Auth/                       # Breeze auth (8 controllers)
│   │   │   ├── DanusSHop/
│   │   │   │   └── ProdukController.php    # ✅ Implemented
│   │   │   ├── Infokom/
│   │   │   │   ├── FormatifController.php  # ✅ Implemented
│   │   │   │   └── KegiatanController.php  # ⚠️ Stub
│   │   │   └── Portal/
│   │   │       ├── InventarisController.php    # ✅ Implemented
│   │   │       └── PenyewaanAlatController.php # ✅ Implemented
│   │   ├── Middleware/
│   │   │   └── HandleInertiaRequests.php
│   │   └── Requests/
│   │       ├── Auth/LoginRequest.php
│   │       ├── ProfileUpdateRequest.php
│   │       ├── ProdukStoreRequest.php
│   │       ├── ProdukUpdateRequest.php
│   │       ├── FormatifStoreRequest.php
│   │       ├── FormatifUpdateRequest.php
│   │       ├── InventarisStoreRequest.php
│   │       ├── InventarisUpdateRequest.php
│   │       └── PenyewaanAlatStoreRequest.php
│   ├── Services/
│   │   ├── BaseService.php
│   │   ├── ProdukService.php
│   │   ├── KegiatanService.php
│   │   ├── FormatifService.php
│   │   ├── InventarisService.php
│   │   ├── PenyewaanAlatService.php
│   │   ├── TransaksiService.php
│   │   └── UserService.php
│   ├── Helpers/
│   │   ├── CurrencyHelper.php
│   │   └── AutoSlugHelper.php
│   └── Models/
│       ├── User.php
│       ├── Role.php
│       ├── Formatif.php
│       ├── Kegiatan.php
│       ├── Transaksi.php
│       ├── Produk.php
│       ├── ShopDetail.php
│       ├── Inventaris.php
│       └── PenyewaanAlat.php
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2026_10_13_010000_create_roles_table.php
│   │   ├── 2026_10_13_010100_create_users_table.php
│   │   ├── 2026_10_13_010200_create_kegiatan_table.php
│   │   ├── 2026_10_13_010300_create_produk_table.php
│   │   ├── 2026_10_13_010400_create_inventaris_alat_table.php
│   │   ├── 2026_10_13_010500_create_transaksi_table.php
│   │   ├── 2026_10_13_010600_create_formatif_table.php
│   │   ├── 2026_10_13_010800_create_penyewaan_table.php
│   │   └── 2026_10_19_124812_create_sessions_table.php
│   ├── factories/
│   ├── seeders/
│   └── database.sqlite                     # ⚠️ Legacy artifact (empty)
├── resources/
│   ├── css/app.css
│   ├── js/
│   │   ├── app.js
│   │   ├── Components/                     # 15 Breeze UI components
│   │   ├── Layouts/
│   │   │   ├── AuthenticatedLayout.vue
│   │   │   └── GuestLayout.vue
│   │   └── Pages/
│   │       ├── Auth/                       # Breeze auth pages
│   │       ├── Profile/
│   │       ├── Dashboard.vue
│   │       ├── Welcome.vue
│   │       ├── Blog/                       # ⚠️ Planned — Formatif pages (Index, Show, Create, Edit)
│   │       ├── Shop/                       # ⚠️ Planned — Produk pages (Index, Show, Create, Edit)
│   │       └── Inventory/                  # ⚠️ Planned — Inventaris & Penyewaan pages
│   └── views/app.blade.php
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── console.php
├── tests/
│   ├── Feature/
│   ├── Unit/
│   └── TestCase.php
├── composer.json
├── package.json
├── vite.config.js
├── tailwind.config.js
├── postcss.config.js
├── jsconfig.json
├── phpunit.xml
├── .env.example
└── ARCHITECTURE.md
```

---

## 13. Role Development Triggers

Trigger keywords untuk memfokuskan AI agent ke context tertentu. Gunakan trigger ini di awal prompt untuk memberitahu AI role mana yang sedang dikerjakan.

### 13.1 Trigger Keywords

| Trigger | Role | Files Scope |
|---|---|---|
| `@shop` | Danus Shop | `app/Http/Controllers/DanusSHop/`, `app/Services/ProdukService.php`, `app/Models/Produk.php`, `resources/js/Pages/Shop/` |
| `@infokom` | Infokom | `app/Http/Controllers/Infokom/`, `app/Services/FormatifService.php`, `app/Services/KegiatanService.php`, `resources/js/Pages/Blog/` |
| `@portal` | Portal | `app/Http/Controllers/Portal/`, `app/Services/InventarisService.php`, `app/Services/PenyewaanAlatService.php`, `resources/js/Pages/Inventory/` |
| `@admin` | Admin/BPH | `app/Http/Controllers/Admin/`, `app/Services/UserService.php`, `resources/js/Pages/Admin/` |
| `@frontend` | Frontend Team | `resources/js/`, `resources/css/`, Vue components, Tailwind |
| `@backend` | Backend Team | `app/Http/Controllers/`, `app/Services/`, `app/Models/`, `database/` |
| `@recap` | Documentation / Graphify | `ARCHITECTURE.md`, `README.md`, `.ai/rules/` |

### 13.2 Intelligent Detection

AI agent akan auto-detect context dari:

1. **Trigger di prompt:** Mulai prompt dengan `@shop`, `@infokom`, dll
2. **Keyword detection:** Kata-kata seperti "produk", "stok", "shop" → trigger `@shop`
3. **File path:** Jika Lagi edit `app/Http/Controllers/DanusSHop/` → auto-switch ke `@shop`

### 13.3 Cara Penggunaan

```text
# Contoh penggunaan trigger

@shop
"tambah fitur diskon produk"

@infokom
"buat halaman blog baru dengan Puck editor"

@portal
"implementasi approve peminjaman alat"

@admin
"tambah halaman management user BPH"

@frontend
"bikin component modal baru"

@backend
"optimasi query inventaris"
```

> **Note:** Detail setiap role ada di `.ai/rules/roles/`

---

## 14. Changelog

| Version | Date | Changes |
| :--- | :--- | :--- |
| 1.2 | 2026-10-21 | Added §13 Role Development Triggers (@shop, @infokom, @portal, @admin, @frontend, @backend) |
| 1.1 | 2026-10-21 | Fixed: PHP version (8.5), RBAC (custom not Spatie), migration status (all present), table names (singular), controller status (implemented vs stub) |
| 1.0 | 2026-10-12 | Initial architecture documentation |
