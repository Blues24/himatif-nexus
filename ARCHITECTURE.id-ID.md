# Arsitektur Sistem — HIMATIF Nexus (`himatif-nexus`)

> **Bahasa / Languages:** [Bahasa Indonesia (id-ID)](./ARCHITECTURE.id-ID.md) · [English (en-US)](./ARCHITECTURE.md)

## 1. Gambaran Umum & Pola Desain

**HIMATIF Nexus** dibangun menggunakan pendekatan **Monolith-SPA (Single Page Application)** dengan memadukan **Laravel (PHP 8.3+)** di sisi backend dan **Vue 3 + Inertia.js** di sisi frontend.

* **Backend:** Laravel menangani authentication, RBAC middleware, Eloquent ORM, validasi bisnis, serta pengelolaan database.
* **Frontend:** Vue 3 + Tailwind CSS menangani antarmuka pengguna yang reaktif tanpa memerlukan pembentukan REST API terpisah.
* **Database:** **PostgreSQL** sebagai relational database management system (engine target — lihat [§3](#3-database-engine--postgresql)).
* **Content Builder (Infokom):** **Puck (`@measured/puck`)** dipadukan dengan **`vue3-colorpicker`** untuk kebutuhan visual drag-and-drop page builder beserta kustomisasi skema warna (Preset Brand & Custom Color Wheel Hex/RGB).

---

## 2. Spesifikasi Teknologi

Versi di bawah mencerminkan apa yang benar-benar dideklarasikan di `composer.json` dan `package.json`. Item bertanda **(rencana)** adalah target teknologi yang **belum terpasang**.

| Layer | Teknologi / Paket | Versi | Status | Fungsi |
| :--- | :--- | :--- | :--- | :--- |
| **Backend Framework** | `laravel/framework` | ^13.17 (PHP ^8.3) | Terpasang | Core application, routing, controllers, ORM |
| **Frontend Framework** | `vue` (Composition API, `<script setup>`) | ^3.4 | Terpasang | Antarmuka pengguna yang reaktif (UI) |
| **Perekat Monolith-SPA** | `inertiajs/inertia-laravel` + `@inertiajs/vue3` | ^2.0 | Terpasang | Pengiriman data/props tanpa REST API manual |
| **Scaffolding Auth** | `laravel/breeze` (+ `laravel/sanctum`, `tightenco/ziggy`) | ^2.4 / ^4.0 / ^2.0 | Terpasang | Alur login, registrasi, reset sandi & profil; named route di JS |
| **Page & Blog Builder** | `@measured/puck` | — | **(rencana)** | Editor blok visual drag-and-drop |
| **Color Picker** | `vue3-colorpicker` | — | **(rencana)** | Color wheel & input hex untuk style kustom |
| **Styling & UI** | `tailwindcss` + `@tailwindcss/forms` | ^3.2 (direktif v3 di `resources/css/app.css`) | Terpasang | Styling CSS utility-first |
| **Build Tool** | `vite` + `laravel-vite-plugin` | ^8.0 / ^3.1 | Terpasang | Bundling aset & HMR |
| **RBAC Engine** | `spatie/laravel-permission` | — | **(rencana)** | Manajemen Role & Permission |
| **Database Engine** | **PostgreSQL** | 18.x / 17.x | **(target)** | Relational database engine |

---

## 3. Database Engine — PostgreSQL

Proyek ini berpindah dari bawaan Laravel (SQLite) ke **PostgreSQL**. `config/database.php` sebenarnya sudah menyertakan blok koneksi `pgsql` yang lengkap (port `5432`, `search_path => 'public'`, `sslmode` lewat `DB_SSLMODE`), sehingga tidak perlu perubahan konfigurasi — yang perlu disesuaikan adalah environment dan keputusan skema.

### 3.1 Mengubah koneksi

```env
# .env — nilai saat ini adalah DB_CONNECTION=sqlite
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=himatif_nexus
DB_USERNAME=himatif
DB_PASSWORD=<secret>
DB_SSLMODE=prefer
```

Ekstensi PHP yang dibutuhkan: `pdo_pgsql` (dan `pgsql` untuk akses pdo langsung). Perbarui `.env.example` bersama `.env` agar seluruh tim melakukan provisioning yang sama.

### 3.2 Perbedaan dialek yang berdampak pada proyek ini

| Permasalahan | MySQL / MariaDB / SQLite | PostgreSQL — penggunaan pengganti |
| :--- | :--- | :--- |
| Konten panjang (`text_konten`) | `LONGTEXT` | **`jsonb`** (lebih diutamakan) atau `text` — **PostgreSQL tidak mengenal `LONGTEXT`** |
| Pencarian layout tree Puck | scan `LIKE` | `jsonb` + GIN index: `$table->jsonb('text_konten')->default('{}')` |
| Identifier | Campuran case-sensitive | Identifier tanpa tanda kuti dilipat ke **huruf kecil** — pakai kolom/tabel `snake_case`, jangan camelCase |
| Kolom enum | `enum()` native | `$table->string('status')->check(DB::raw(...))` atau `CREATE TYPE ... AS ENUM` native lewat `DB::statement` |
| Boolean / JSON | `tinyint(1)` / `json` blob teks | `boolean` native, `jsonb` native dengan operator containment sungguhan (`@>`, `?`) |
| Sequence | `AUTO_INCREMENT` | `$table->id()` dipetakan ke `bigint generated ... as identity` — tidak perlu penanganan khusus |
| Panjang nama index | 64 karakter, dipotong diam-diam | **Batas 63 byte, memunculkan error** — jaga nama index komposit tetap pendek |

### 3.3 Hambatan migrasi yang masih terbuka

Terverifikasi terhadap working tree — hal-hal ini harus diselesaikan sebelum `php artisan migrate` berhasil di PostgreSQL:

1. **Tabel framework hilang.** Migration bawaan `0001_01_01_*_create_cache_table.php` dan `..._create_jobs_table.php` telah dihapus, sedangkan `.env` masih memakai `SESSION_DRIVER=database`. Tambahkan kembali `sessions`, `cache`, dan `jobs` (atau ubah `SESSION_DRIVER`/`CACHE_STORE` menjadi `file`) sebelum bermigrasi.
2. **Migration masih stub.** Seluruh tabel domain saat ini hanya berisi `$table->id()` dan `$table->timestamps()` — kolom bisnis belum ada sama sekali.
3. **`database/database.sqlite`** adalah sisa artefak development. Pastikan `DB_CONNECTION=pgsql` dipakai di CI/staging agar tidak ada yang diam-diam jatuh kembali ke SQLite.

---

## 4. Skema Database (Ringkasan Entitas)

Tabel sebagaimana dibuat oleh migration yang ada di `database/migrations/`. Kolom *Tujuan* adalah niat desain; kolom *Kolom saat ini* adalah kondisi nyata terverifikasi.

| Tabel | Model | Tujuan | Kolom saat ini |
| :--- | :--- | :--- | :--- |
| `users` | `User` | Akun untuk keempat tim | `id`, `timestamps` *(stub)* |
| `roles` | `Role` | Role RBAC (BPH / Infokom / Portal / Danus Shop) | `id`, `timestamps` *(stub)* |
| `formatifs` | `Formatif` | Artikel Formatif & konten Puck | `id`, `timestamps` *(stub)* |
| `kegiatans` | `Kegiatan` | Kegiatan/acara organisasi | `id`, `timestamps` *(stub)* |
| `transaksis` | `Transaksi` | Transaksi lintas modul | `id`, `timestamps` *(stub)* |
| `produks` | `Produk` (+ `ShopDetail`) | Katalog produk Danus Shop | `id`, `timestamps` *(stub)* |
| `inventaris` | `Inventaris` | Inventaris alat/aset HM | `id`, `timestamps` *(stub)* |
| `penyewaan_alats` | `PenyewaanAlat` | Pengajuan peminjaman alat | `id`, `timestamps` *(stub)* |

> **Catatan penamaan:** `Pluralizer` bawaan Laravel menghasilkan `kegiatans`, `transaksis`, dan `produks`. Bentuk ini salah secara kaidah bahasa Indonesia, tetapi konsisten dengan model dan migration yang di-generate — biarkan apa adanya daripada mengubah nama `$table` secara manual, yang akan merusak inferensi `Model::$table`.

---

## 5. Arsitektur Visual Page Builder (Integrasi Puck)

1. **Developer Config:** Mendefinisikan registrasi komponen Vue templat (`BannerBlock`, `CardLomba`, `GridBlock`) dan skema *props* di `resources/js/Builder/puckConfig.js` *(direktori belum dibuat)*.
2. **Infokom Editor Mode:**
   * Melakukan *drag-and-drop* komponen ke kanvas.
   * Mengatur teks, opsi layout, dan skema warna via *sidebar control*.
   * Memilih warna via **Preset Brand HIMATIF** atau **Custom Color Picker Wheel (`vue3-colorpicker`)**.
3. **Storage Engine:** Hasil *layout* berwujud JSON tree disimpan secara bersih di kolom `text_konten` pada tabel `formatifs` — bertipe **`jsonb`** di PostgreSQL (dokumen lama menyebut `LONGTEXT`, yang tidak didukung PostgreSQL).
4. **Public Render Mode:** Halaman `Blog/Show.vue` membaca JSON dan meloop komponen menggunakan dynamic component Vue: `<component :is="block.component" v-bind="block.props" />`.

---

## 6. Matriks Kepemilikan Folder & Batas Akses Tim

Untuk menghindari terjadinya *merge conflict* pada saat kolaborasi tim:

| Folder / File Path | Tim Backend | Tim Frontend | Deskripsi & Batasan Akses |
| :--- | :--- | :--- | :--- |
| `app/Http/Controllers/` | **Full Access** | Read Only | Backend mengatur logika bisnis & merender Inertia View |
| `app/Http/Requests/` | **Full Access** | No Access | Backend mengatur kelas validasi Form Request |
| `app/Models/` | **Full Access** | No Access | Backend mengelola Eloquent ORM & relasi database |
| `app/Http/Middleware/` | **Full Access** | Read Only | Backend mengelola middleware RBAC & share Inertia |
| `database/migrations/` | **Full Access** | No Access | Backend mengelola skema DDL **PostgreSQL** |
| `database/factories/` & `database/seeders/` | **Full Access** | Read Only | Backend menyiapkan data fake & seed |
| `routes/` | **Full Access** | Read Only | Backend mendaftarkan endpoint rute & RBAC middleware |
| `resources/js/Pages/` | Read Only | **Full Access** | Frontend menyusun tampilan halaman Vue per modul |
| `resources/js/Components/` | Read Only | **Full Access** | Frontend membuat re-usable UI (Navbar, Modal, Table) |
| `resources/js/Layouts/` | Read Only | **Full Access** | Frontend mengelola kerangka layout halaman |
| `resources/js/Builder/` | Read Only | **Full Access** | Frontend mengelola Puck Page Builder + Color Picker *(rencana)* |
| `resources/css/` | Read Only | **Full Access** | Frontend mengelola Tailwind CSS styling |
| `resources/views/app.blade.php` | **Full Access** | Read Only | Backend mengelola root template Inertia |
| `config/` | **Full Access** | No Access | Backend mengelola konfigurasi sistem, koneksi database & RBAC |

> Disesuaikan dari revisi sebelumnya: folder frontend hasil Breeze (`resources/js/Layouts/`, `resources/js/Pages/Auth`, `resources/js/Pages/Profile`) sudah ikut ter-commit oleh scaffolding, sehingga `resources/js/Components/` dan `resources/css/` diubah dari *No Access* menjadi *Read Only* bagi backend — backend harus tetap bisa menambal kode scaffold yang dihasilkannya sendiri.

---

## 7. Prinsip SOLID & Arsitektur Berlapis

Kontroler harus tetap tipis — mereka hanyalah gerbang HTTP, bukan mesin logika bisnis. Aplikasi ini menjunjung lima prinsip SOLID di level arsitektur.

### 7.1 Single Responsibility Principle (SRP)

| Layer | Tanggung Jawab Tunggal | Contoh |
|---|---|---|
| **Controller** | Validasi input → panggil service → kembalikan respons | `ProdukController@store` menerima request, memanggil `ProdukService::create`, redirect |
| **Model** | Definisi data + relasi | `Produk::$fillable`, `User::role()` |
| **Service** | Aturan bisnis & operasi lintas-model | `ProdukService::adjustStock()`, `PenyewaanAlatService::handleRequest()` |
| **Form Request** | Hanya validasi | `ProfileUpdateRequest`, custom domain Requests |
| **Helper** | Utilitas murni, stateless | `CurrencyHelper::format()`, `AutoSlugHelper::generate()` |

```text
Client → Controller → Service → Model ← Helper
         (HTTP)       (rules)    (data)   (utilities)
```

### 7.2 Open/Closed Principle (OCP)

Fitur baru memperluas perilaku tanpa mengubah kode yang ada:

- **Services** mewarisi dari `BaseService` dan menimpa hook (`afterCreate`, `afterUpdate`) daripada menulis ulang CRUD
- **Event subscriber** menambahkan efek samping tanpa menyentuh kontroler
- **Middleware pipeline** menyusun gerbang baru tanpa mengedit definisi rute

### 7.3 Liskov Substitution Principle (LSP)

Semua service menerapkan kontrak bersama secara implisit melalui `BaseService`. Setiap service konkret dapat ditukar dengan parameter penelur (caller) yang sama:

```php
// Pemanggil tidak tahu apakah ProdukService atau MockProdukService digunakan
public function __construct(public Product $service) {}
public function index(): Response { /* bekerja dengan subclass BaseService apapun */ }
```

### 7.4 Interface Segregation Principle (ISP)

Tanggung jawab kecil dan terfokus per kelas:

- Controller **tidak** menyuntikkan service multi-modul yang besar — masing-masing hanya mendapat satu domain service
- Model mendefinisikan **hanya** relasi yang benar-benar dimilikinya (anti-pattern god-model)
- Form Request memvalidasi **hanya** formulir spesifiknya — tidak ada kelas validasi bersama

### 7.5 Dependency Inversion Principle (DIP)

Modul tingkat tinggi bergantung pada abstraksi, bukan implementasi konkret:

```php
class ProdukController extends Controller
{
    // Bergantung pada abstraction ProductService (implisit via kontrak BaseService)
    public function __construct(private readonly Product $service) {}
}
```

Hal ini memungkinkan penukaran implementasi (misalnya mocking untuk tes, driver DB berbeda) tanpa menyentuh kode kontroler.

### 7.6 Tata Letak Direktori Baru

Pemisahan SOLID menambahkan dua direktori di bawah `app/`:

```text
app/
├── Http/
│   └── Controllers/           # Penggerak HTTP tipis — hanya gerbang
├── Services/                  # Logika bisnis — otak sesungguhnya
│   ├── BaseService.php        # Kerangka CRUD bersama
│   ├── ProdukService.php      # Aturan domain Danus Shop
│   ├── KegiatanService.php    # Manajemen kegiatan
│   ├── FormatifService.php    # Penerbitan konten
│   ├── InventarisService.php  # Pelacakan inventaris
│   ├── PenyewaanAlatService.php  # Alur peminjaman
│   ├── TransaksiService.php   # Pencatatan transaksi
│   └── UserService.php        # Manajemen pengguna & penugasan RBAC
├── Helpers/                   # Utilitas murni — tanpa kopling framework
│   ├── CurrencyHelper.php     # Format & parsing IDR
│   └── AutoSlugHelper.php     # Pembuatan slug unik
├── Models/                    # Entitas Eloquent — data + relasi
└── ...
```

#### Tanggung Jawab Controller (Yang Harus Dilakukan)

| Metode | Apa Yang Dilakukan |
|---|---|
| `index()` | Memanggil `$service->paginate($filter)` → kembalikan respons Inertia |
| `store(Request $request)` | Validasi → `$service->create($validated)` → redirect |
| `show($id)` | Memanggil `$service->findOrFail($id)` → kembalikan respons Inertia |
| `update(Request $request, $id)` | Validasi → `$service->update($id, $validated)` → redirect |
| `destroy($id)` | Memanggil `$service->delete($id)` → redirect |

**Apa JANGAN diletakkan di controller:** pengecekan stok, perhitungan harga, traversal relasi, notifikasi, pencatatan audit, query kondisional kompleks — semuanya masuk ke Services.

---

## 8. Struktur Direktori (`himatif-nexus`)

Dibangkitkan dari working tree yang sebenarnya (vendor/, node_modules/, dan isi storage/ disederhanakan). Folder bertanda **(rencana)** belum ada.

```text
himatif-nexus/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php              # Base controller (abstract)
│   │   │   ├── ProfileController.php       # Root-level (scaffolding Breeze profile)
│   │   │   ├── Admin/                      # Manajemen BPH
│   │   │   │   └── UserController.php
│   │   │   ├── Auth/                       # Controller auth Breeze (login, register,
│   │   │   │                               #   reset sandi, verifikasi email, ... x8)
│   │   │   ├── DanusSHop/
│   │   │   │   └── ProdukController.php    # Katalog Danus Shop
│   │   │   ├── Infokom/
│   │   │   │   ├── FormatifController.php  # Artikel Formatif + konten Puck
│   │   │   │   └── KegiatanController.php  # Kegiatan/acara
│   │   │   └── Portal/
│   │   │       ├── InventarisController.php
│   │   │       └── PenyewaanAlatController.php
│   │   ├── Middleware/
│   │   │   └── HandleInertiaRequests.php   # Shared props Inertia (RBAC guard: rencana)
│   │   └── Requests/                       # Validasi Form Request
│   │       ├── Auth/LoginRequest.php
│   │       └── ProfileUpdateRequest.php
│   ├── Services/                           # Layer logika bisnis (SRP, OCP)
│   │   ├── BaseService.php                 # Kerangka CRUD bersama
│   │   ├── ProdukService.php               # Aturan domain Danus Shop
│   │   ├── KegiatanService.php             # Manajemen kegiatan
│   │   ├── FormatifService.php             # Penerbitan konten
│   │   ├── InventarisService.php           # Pelacakan inventaris
│   │   ├── PenyewaanAlatService.php        # Alur peminjaman
│   │   ├── TransaksiService.php            # Pencatatan transaksi
│   │   └── UserService.php                 # Manajemen pengguna & penugasan RBAC
│   ├── Helpers/                            # Utilitas murni — tanpa kopling framework
│   │   ├── CurrencyHelper.php              # Format & parsing IDR
│   │   └── AutoSlugHelper.php              # Pembuatan slug unik
│   ├── Models/                             # Entitas Eloquent
│   │   ├── User.php          Role.php      # Akun & RBAC
│   │   ├── Formatif.php      Kegiatan.php  # Modul konten
│   │   ├── Transaksi.php     Produk.php    # Komersial
│   │   ├── ShopDetail.php                  # Rincian item Produk
│   │   └── Inventaris.php    PenyewaanAlat.php   # Portal aset
│   └── Providers/AppServiceProvider.php
├── bootstrap/                              # Bootstrap framework + cache/
├── config/                                 # app, auth, database (pgsql), cache, session,
│                                         #   filesystems, logging, mail, queue, services
├── database/
│   ├── migrations/                         # DDL PostgreSQL (8 tabel, kolom masih stub)
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
│   ├── seeders/                            # DatabaseSeeder + satu seeder per modul,
│   │                                       #   termasuk RoleSeeder (role & akun admin)
│   └── database.sqlite                     # Artefak dev lama — bukan engine target
├── public/                                 # index.php, favicon.ico, robots.txt
├── resources/
│   ├── css/app.css                         # Direktif Tailwind v3 @tailwind
│   ├── js/
│   │   ├── app.js                          # Bootstrap aplikasi Inertia
│   │   ├── Components/                     # 15 komponen UI bersama hasil Breeze
│   │   │                                   #   (ApplicationLogo, Modal, Dropdown,
│   │   │                                   #    PrimaryButton, TextInput, ...)
│   │   ├── Layouts/
│   │   │   ├── AuthenticatedLayout.vue
│   │   │   └── GuestLayout.vue
│   │   ├── Builder/                        # (rencana) Puck page builder
│   │   │   ├── Components/                 # (rencana) BannerBlock.vue, GridBlock.vue
│   │   │   ├── Controls/                   # (rencana) SidebarColorControl.vue
│   │   │   └── puckConfig.js               # (rencana) Konfigurasi registry Puck
│   │   └── Pages/
│   │       ├── Auth/                       # Login, Register, ForgotPassword,
│   │       │                               #   ResetPassword, ConfirmPassword, VerifyEmail
│   │       ├── Profile/                    # Edit.vue + Partials/ (3 komponen form)
│   │       ├── Dashboard.vue
│   │       ├── Welcome.vue
│   │       ├── Admin/                      # (rencana) Halaman manajemen BPH
│   │       ├── Blog/                       # (rencana) Index, Show, Editor (Puck)
│   │       ├── Inventory/                  # (rencana) Katalog & peminjaman alat
│   │       ├── Kegiatan/                   # (rencana) Modul kegiatan
│   │       └── Shop/                       # (rencana) Modul Danus Shop
│   └── views/app.blade.php                 # Root template Inertia
├── routes/
│   ├── web.php                             # Rute web Inertia + RBAC middleware
│   ├── auth.php                            # Rute autentikasi Breeze
│   └── console.php
├── storage/                                # app/{private,public}, framework/, logs/
├── tests/
│   ├── Feature/ (Auth/ x6, ProfileTest, ExampleTest)
│   ├── Unit/ (ExampleTest)
│   └── TestCase.php
├── vendor/                                 # Dependensi Composer
├── node_modules/                           # Dependensi npm
├── composer.json  package.json             # Manifest dependensi
├── vite.config.js  tailwind.config.js  postcss.config.js
├── jsconfig.json                           # Alias path "@/*" -> "resources/js/*"
├── phpunit.xml  .env.example  artisan
└── AGENTS.md  CLAUDE.md  README.md  ARCHITECTURE.id-ID.md
```
