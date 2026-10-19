# System Architecture — HIMATIF Nexus (`himatif-nexus`)

> **Languages / Bahasa:** [English (en-US)](./ARCHITECTURE.md) · [Bahasa Indonesia (id-ID)](./ARCHITECTURE.id-ID.md)

## 1. Executive Overview & Design Pattern

**HIMATIF Nexus** is built as a **Monolith-SPA (Single Page Application)**, pairing **Laravel (PHP 8.3+)** on the backend with **Vue 3 + Inertia.js** on the frontend.

* **Backend:** Laravel owns authentication, the RBAC middleware, the Eloquent ORM, business validation, and database management.
* **Frontend:** Vue 3 + Tailwind CSS render a reactive user interface, without the need for a separate REST API.
* **Database:** **PostgreSQL** as the relational database management system (target engine — see [§3](#3-database-engine--postgresql)).
* **Content Builder (Infokom):** **Puck (`@measured/puck`)** combined with **`vue3-colorpicker`** for a visual drag-and-drop page builder plus color-scheme customization (Brand Presets & a custom Hex/RGB color wheel).

---

## 2. Technical Stack Specification

Versions below reflect what is actually declared in `composer.json` and `package.json`. Items marked **(planned)** are target stack that is **not installed yet**.

| Layer | Technology / Package | Version | Status | Function |
| :--- | :--- | :--- | :--- | :--- |
| **Backend Framework** | `laravel/framework` | ^13.17 (PHP ^8.3) | Installed | Core application, routing, controllers, ORM |
| **Frontend Framework** | `vue` (Composition API, `<script setup>`) | ^3.4 | Installed | Reactive user interface (UI) |
| **Monolith-SPA Glue** | `inertiajs/inertia-laravel` + `@inertiajs/vue3` | ^2.0 | Installed | Data/props passing without a manual REST API |
| **Auth Scaffolding** | `laravel/breeze` (+ `laravel/sanctum`, `tightenco/ziggy`) | ^2.4 / ^4.0 / ^2.0 | Installed | Login, registration, password & profile flows; named routes in JS |
| **Page & Blog Builder** | `@measured/puck` | — | **(planned)** | Visual drag-and-drop block editor |
| **Color Picker** | `vue3-colorpicker` | — | **(planned)** | Color wheel & hex input for custom styles |
| **Styling & UI** | `tailwindcss` + `@tailwindcss/forms` | ^3.2 (v3 directives in `resources/css/app.css`) | Installed | Utility-first CSS styling |
| **Build Tool** | `vite` + `laravel-vite-plugin` | ^8.0 / ^3.1 | Installed | Asset bundling & HMR |
| **RBAC Engine** | `spatie/laravel-permission` | — | **(planned)** | Role & permission management |
| **Database Engine** | **PostgreSQL** | 18.x / 17.x | **(target)** | Relational database engine |

---

## 3. Database Engine — PostgreSQL

The project is switching from the Laravel default (SQLite) to **PostgreSQL**. `config/database.php` already ships a fully defined `pgsql` connection block (port `5432`, `search_path => 'public'`, `sslmode` via `DB_SSLMODE`), so no config changes are required — only environment and schema decisions.

### 3.1 Switching the connection

```env
# .env — current value is DB_CONNECTION=sqlite
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=himatif_nexus
DB_USERNAME=himatif
DB_PASSWORD=<secret>
DB_SSLMODE=prefer
```

Required PHP extensions: `pdo_pgsql` (and `pgsql` for direct pdo access). Update `.env.example` alongside `.env` so the rest of the team provisions identically.

### 3.2 Dialect differences that affect this project

| Concern | MySQL / MariaDB / SQLite | PostgreSQL — what to use instead |
| :--- | :--- | :--- |
| Long content body (`text_konten`) | `LONGTEXT` | **`jsonb`** (preferred) or `text` — **PostgreSQL has no `LONGTEXT`** |
| Puck layout tree search | `LIKE` scans | `jsonb` + GIN index: `$table->jsonb('text_konten')->default('{}')` |
| Identifiers | Case-sensitive mix | Unquoted identifiers fold to **lowercase** — use `snake_case` columns/tables, never camelCase |
| Enum columns | Native `enum()` | `$table->string('status')->check(DB::raw(...))` or a native `CREATE TYPE ... AS ENUM` via `DB::statement` |
| Boolean / JSON | `tinyint(1)` / `json` text blob | Native `boolean`, native `jsonb` with real containment operators (`@>`, `?`) |
| Sequences | `AUTO_INCREMENT` | `$table->id()` maps to `bigint generated ... as identity` — no special handling needed |
| Index name length | 64 chars, silently truncated | **63-byte limit, raises an error** — keep composite index names short |

### 3.3 Open migration blockers

Verified against the working tree — these must be resolved before `php artisan migrate` succeeds on PostgreSQL:

1. **Framework tables are missing.** The stock `0001_01_01_*_create_cache_table.php` and `..._create_jobs_table.php` migrations were deleted, while `.env` still sets `SESSION_DRIVER=database`. Re-add `sessions`, `cache`, and `jobs` (or switch `SESSION_DRIVER`/`CACHE_STORE` to `file`) before migrating.
2. **Migrations are stubs.** Every domain table currently contains only `$table->id()` and `$table->timestamps()` — no business columns exist yet.
3. **`database/database.sqlite`** is a leftover dev artifact. Keep `DB_CONNECTION=pgsql` in CI/staging so nothing silently falls back to SQLite.

---

## 4. Database Schema (Entity Overview)

Tables as created by the migrations currently in `database/migrations/`. The *Purpose* column is the design intent; the *Columns* column is the verified reality.

| Table | Model | Purpose | Columns today |
| :--- | :--- | :--- | :--- |
| `users` | `User` | Accounts for all four teams | `id`, `timestamps` *(stub)* |
| `roles` | `Role` | RBAC roles (BPH / Infokom / Portal / Danus Shop) | `id`, `timestamps` *(stub)* |
| `formatifs` | `Formatif` | Formatif articles & Puck content | `id`, `timestamps` *(stub)* |
| `kegiatans` | `Kegiatan` | Organizational activities/events | `id`, `timestamps` *(stub)* |
| `transaksis` | `Transaksi` | Transactions across modules | `id`, `timestamps` *(stub)* |
| `produks` | `Produk` (+ `ShopDetail`) | Danus Shop product catalog | `id`, `timestamps` *(stub)* |
| `inventaris` | `Inventaris` | HM asset/equipment inventory | `id`, `timestamps` *(stub)* |
| `penyewaan_alats` | `PenyewaanAlat` | Equipment loan requests | `id`, `timestamps` *(stub)* |

> **Naming note:** Laravel's default `Pluralizer` produces `kegiatans`, `transaksis`, and `produks`. These are grammatically wrong in Indonesian but are consistent with the generated models and migrations — keep them as-is rather than hand-editing `$table` names, which would break `Model::$table` inference.

---

## 5. Visual Page Builder Architecture (Puck Integration)

1. **Developer Config:** Register Vue template components (`BannerBlock`, `CardLomba`, `GridBlock`) and their *props* schema in `resources/js/Builder/puckConfig.js` *(directory not created yet)*.
2. **Infokom Editor Mode:**
   * Drag-and-drop components onto the canvas.
   * Configure text, layout options, and color scheme via the *sidebar control*.
   * Pick colors from the **HIMATIF Brand Presets** or the **custom color picker wheel (`vue3-colorpicker`)**.
3. **Storage Engine:** The resulting *layout* is stored as a clean JSON tree in the `text_konten` column of the `formatifs` table — **`jsonb`** under PostgreSQL (formerly documented as `LONGTEXT`, which PostgreSQL does not support).
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
|---|---|---|
| **Controller** | Validate input → call service → return response | `ProdukController@store` receives request, calls `ProdukService::create`, redirects |
| **Model** | Data definition + relationships | `Produk::$fillable`, `User::role()` |
| **Service** | Business rules & cross-model operations | `ProdukService::adjustStock()`, `PenyewaanAlatService::handleRequest()` |
| **Form Request** | Validation only | `ProfileUpdateRequest`, custom domain Requests |
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
    public function __construct(private readonly Product $service) {}
}
```

This allows swapping implementations (e.g., mocking for tests, different DB drivers) without touching controller code.

### 7.6 New Directory Layout

The SOLID split adds two directories under `app/`:

```text
app/
├── Http/
│   └── Controllers/           # Thin HTTP handlers — just gateways
├── Services/                  # Business logic — the real "brain"
│   ├── BaseService.php        # Shared CRUD base (extends nothing external)
│   ├── ProdukService.php      # Danus Shop domain rules
│   ├── KegiatanService.php    # Activity management
│   ├── FormatifService.php    # Content publishing
│   ├── InventarisService.php  # Inventory tracking
│   ├── PenyewaanAlatService.php  # Rental workflow
│   ├── TransaksiService.php   # Transaction recording
│   └── UserService.php        # User management & RBAC assignment
├── Helpers/                   # Pure utilities — no framework coupling
│   ├── CurrencyHelper.php     # IDR formatting & parsing
│   └── AutoSlugHelper.php     # Unique slug generation
├── Models/                    # Eloquent entities — data + relations
└── ...
```

#### Controller Responsibilities (What They Should Do)

| Method | What It Does |
|---|---|
| `index()` | Calls `$service->paginate($filter)` → returns Inertia response |
| `store(Request $request)` | Validates → `$service->create($validated)` → redirects |
| `show($id)` | Calls `$service->findOrFail($id)` → returns Inertia response |
| `update(Request $request, $id)` | Validates → `$service->update($id, $validated)` → redirects |
| `destroy($id)` | Calls `$service->delete($id)` → redirects |

**What NOT to put in controllers:** stock checks, price calculations, relationship traversals, notifications, audit logging, complex conditional queries — all go to Services.

---

## 8. Directory Structure (`himatif-nexus`)

Generated from the actual working tree (vendor/, node_modules/, and storage/ internals omitted). Folders marked **(planned)** do not exist yet.

```text
himatif-nexus/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php              # Base controller (abstract)
│   │   │   ├── ProfileController.php       # Root-level (Breeze profile flow)
│   │   │   ├── Admin/                      # BPH management
│   │   │   │   └── UserController.php
│   │   │   ├── Auth/                       # Breeze auth controllers (login, register,
│   │   │   │                               #   reset password, verify email, ... x8)
│   │   │   ├── DanusSHop/
│   │   │   │   └── ProdukController.php    # Danus Shop catalog
│   │   │   ├── Infokom/
│   │   │   │   ├── FormatifController.php  # Formatif articles + Puck content
│   │   │   │   └── KegiatanController.php  # Activities/events
│   │   │   └── Portal/
│   │   │       ├── InventarisController.php
│   │   │       └── PenyewaanAlatController.php
│   │   ├── Middleware/
│   │   │   └── HandleInertiaRequests.php   # Inertia shared props (RBAC guard: planned)
│   │   └── Requests/                       # Form Request validation
│   │       ├── Auth/LoginRequest.php
│   │       └── ProfileUpdateRequest.php
│   ├── Services/                           # Business logic layer (SRP, OCP)
│   │   ├── BaseService.php                 # Shared CRUD skeleton
│   │   ├── ProdukService.php               # Danus Shop domain rules
│   │   ├── KegiatanService.php             # Activity management
│   │   ├── FormatifService.php             # Content publishing
│   │   ├── InventarisService.php           # Inventory tracking
│   │   ├── PenyewaanAlatService.php        # Rental workflow
│   │   ├── TransaksiService.php            # Transaction recording
│   │   └── UserService.php                 # User management & RBAC assignment
│   ├── Helpers/                            # Pure utilities — no framework coupling
│   │   ├── CurrencyHelper.php              # IDR formatting & parsing
│   │   └── AutoSlugHelper.php              # Unique slug generation
│   ├── Models/                             # Eloquent entities
│   │   ├── User.php          Role.php      # Accounts & RBAC
│   │   ├── Formatif.php      Kegiatan.php  # Content modules
│   │   ├── Transaksi.php     Produk.php    # Commerce
│   │   ├── ShopDetail.php                  # Produk line items
│   │   └── Inventaris.php    PenyewaanAlat.php   # Asset portal
│   └── Providers/AppServiceProvider.php
├── bootstrap/                              # Framework bootstrap + cache/
├── config/                                 # app, auth, database (pgsql), cache, session,
│                                         #   filesystems, logging, mail, queue, services
├── database/
│   ├── migrations/                         # PostgreSQL DDL (8 tables, stub columns)
│   │   ├── 2026_10_12_200200_create_users_table.php
│   │   ├── 2026_10_12_200218_create_formatifs_table.php
│   │   ├── 2026_10_12_200232_create_kegiatans_table.php
│   │   ├── 2026_10_12_200246_create_transaksis_table.php
│   │   ├── 2026_10_12_200816_create_produks_table.php
│   │   ├── 2026_10_12_200830_create_inventaris_table.php
│   │   ├── 2026_10_12_200852_create_penyewaan_alats_table.php
│   │   └── 2026_10_12_200929_create_roles_table.php
│   ├── factories/                          # User, Formatif, Kegiatan, Transaksi,
│   │                                       #   Produk, Inventaris, PenyewaanAlat
│   ├── seeders/                            # DatabaseSeeder + one seeder per module,
│   │                                       #   incl. RoleSeeder (roles & admin account)
│   └── database.sqlite                     # Legacy dev artifact — not the target engine
├── public/                                 # index.php, favicon.ico, robots.txt
├── resources/
│   ├── css/app.css                         # Tailwind v3 @tailwind directives
│   ├── js/
│   │   ├── app.js                          # Inertia app bootstrap
│   │   ├── Components/                     # 15 shared Breeze UI components
│   │   │                                   #   (ApplicationLogo, Modal, Dropdown,
│   │   │                                   #    PrimaryButton, TextInput, ...)
│   │   ├── Layouts/
│   │   │   ├── AuthenticatedLayout.vue
│   │   │   └── GuestLayout.vue
│   │   ├── Builder/                        # (planned) Puck page builder
│   │   │   ├── Components/                 # (planned) BannerBlock.vue, GridBlock.vue
│   │   │   ├── Controls/                   # (planned) SidebarColorControl.vue
│   │   │   └── puckConfig.js               # (planned) Puck registry config
│   │   └── Pages/
│   │       ├── Auth/                       # Login, Register, ForgotPassword,
│   │       │                               #   ResetPassword, ConfirmPassword, VerifyEmail
│   │       ├── Profile/                    # Edit.vue + Partials/ (3 form components)
│   │       ├── Dashboard.vue
│   │       ├── Welcome.vue
│   │       ├── Admin/                      # (planned) BPH management screens
│   │       ├── Blog/                       # (planned) Index, Show, Editor (Puck)
│   │       ├── Inventory/                  # (planned) Catalog & equipment loans
│   │       ├── Kegiatan/                   # (planned) Activity module
│   │       └── Shop/                       # (planned) Danus Shop module
│   └── views/app.blade.php                 # Inertia root template
├── routes/
│   ├── web.php                             # Inertia web routes + RBAC middleware
│   ├── auth.php                            # Breeze authentication routes
│   └── console.php
├── storage/                                # app/{private,public}, framework/, logs/
├── tests/
│   ├── Feature/ (Auth/ x6, ProfileTest, ExampleTest)
│   ├── Unit/ (ExampleTest)
│   └── TestCase.php
├── vendor/                                 # Composer dependencies
├── node_modules/                           # npm dependencies
├── composer.json  package.json             # Dependency manifests
├── vite.config.js  tailwind.config.js  postcss.config.js
├── jsconfig.json                           # "@/*" -> "resources/js/*" path alias
├── phpunit.xml  .env.example  artisan
└── AGENTS.md  CLAUDE.md  README.md  ARCHITECTURE.md
```
