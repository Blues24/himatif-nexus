# System Architecture — HIMATIF Nexus (`himatif-nexus`)

## 1. Executive Overview & Design Pattern
**HIMATIF Nexus** dibangun menggunakan pendekatan **Monolith-SPA (Single Page Application)** dengan memadukan **Laravel (PHP 8.x)** di sisi backend dan **Vue 3 + Inertia.js** di sisi frontend.

* **Backend:** Laravel menangani authentication, RBAC middleware, Eloquent ORM, validasi bisnis, serta pengelolaan database.
* **Frontend:** Vue 3 + Tailwind CSS menangani antarmuka pengguna yang reaktif tanpa memerlukan pembentukan REST API terpisah.
* **Database:** Postgresql sebagai relational database management system.
* **Content Builder (Infokom):** **Puck (`@measured/puck`)** dipadukan dengan **`vue3-colorpicker`** untuk kebutuhan visual drag-and-drop page builder beserta kustomisasi skema warna (Preset Brand & Custom Color Wheel Hex/RGB).

---

## 2. Technical Stack Specification
| Layer | Technology / Package | Function |
| :--- | :--- | :--- |
| **Backend Framework** | Laravel 13.x (PHP 8.x) | Core application, routing, controllers, ORM |
| **Frontend Framework** | Vue 3 (Composition API `<script setup>`) | Reaktif User Interface (UI) |
| **Monolith-SPA Glue** | Inertia.js | Data/Props passing tanpa REST API manual |
| **Page & Blog Builder**| `@measured/puck` | Visual Drag-and-Drop block editor |
| **Color Picker** | `vue3-colorpicker` | Color wheel & hex input untuk custom style |
| **Styling & UI** | Tailwind CSS v3 | Utility-first CSS styling |
| **RBAC Engine** | `spatie/laravel-permission` | Role & Permission Management |
| **Database Engine** | Postgresql 18.x / 17.x | Relational Database Engine |

---

## 3. Database Schema Architecture (MariaDB DDL)

### 3.1. DDL Script
```sql
-- 1. Roles Table
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_role VARCHAR(50) NOT NULL
);

-- 2. Users Table
CREATE TABLE users (
    user_uuid VARCHAR(36) PRIMARY KEY,
    role_id INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nim VARCHAR(20) NOT NULL UNIQUE,
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE
);



-- 3. Inventaris Alat Table
CREATE TABLE inventaris_alat (
    inventaris_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    stok INT NOT NULL DEFAULT 0,
    kondisi VARCHAR(50) NOT NULL
);

-- 4. Peminjaman Table
CREATE TABLE peminjaman (
    id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    inventaris_id INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE NULL,
    status_pinjam VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_uuid) ON DELETE CASCADE,
    FOREIGN KEY (inventaris_id) REFERENCES inventaris_alat(inventaris_id) ON DELETE CASCADE
);

-- 5. Produk Table
CREATE TABLE produk (
    produk_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(100) NOT NULL,
    deskripsi_produk TEXT NULL,
    harga_produk DECIMAL(12, 2) NOT NULL,
    stok_produk INT NOT NULL DEFAULT 0,
    gambar_produk VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 6. Transaksi Table
CREATE TABLE transaksi (
    transaksi_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    total_bayar DECIMAL(12, 2) NOT NULL,
    status_transaksi VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_uuid) ON DELETE CASCADE
);

-- 7. Shop Detail Table
CREATE TABLE shop_detail (
    shop_id INT AUTO_INCREMENT PRIMARY KEY,
    transaksi_id INT NOT NULL,
    produk_id INT NOT NULL,
    jumlah_beli INT NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (transaksi_id) REFERENCES transaksi(transaksi_id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(produk_id) ON DELETE CASCADE
);

-- 8. Kegiatan Table
CREATE TABLE kegiatan (
    kegiatan_id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kegiatan VARCHAR(100) NOT NULL,
    deskripsi_kegiatan TEXT NULL,
    tanggal_pelaksanaan DATE NOT NULL,
    status_kegiatan VARCHAR(50) NOT NULL
);

-- 9. FORMATIF Table (Blog / Content)
CREATE TABLE formatif (
    formatif_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    judul VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    kategori VARCHAR(100) NOT NULL,
    text_konten LONGTEXT NOT NULL COMMENT 'Puck JSON Tree Output',
    thumbnail_path VARCHAR(255) NULL,
    status VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_uuid) ON DELETE CASCADE
);

```

---

## 4. Visual Page Builder Architecture (Puck Integration)

1. **Developer Config:** Mendefinisikan registrasi komponen Vue templat (`BannerBlock`, `CardLomba`, `GridBlock`) dan skema *props* di `resources/js/Builder/puckConfig.js`.
2. **Infokom Editor Mode:**
* Melakukan *drag-and-drop* komponen ke kanvas.
* Mengatur teks, opsi layout, dan skema warna via *sidebar control*.
* Memilih warna via **Preset Brand HIMATIF** atau **Custom Color Picker Wheel (`vue3-colorpicker`)**.


3. **Storage Engine:** Hasil *layout* berwujud JSON tree disimpan secara bersih di kolom `text_konten` (`LONGTEXT`) pada tabel `formatif`.
4. **Public Render Mode:** Halaman `Blog/Show.vue` membaca JSON dan meloop komponen menggunakan dynamic component Vue: `<component :is="block.component" v-bind="block.props" />`.

---

## 5. Team Folder Ownership & Access Boundary Matrix

Untuk menghindari terjadinya *merge conflict* pada saat kolaborasi tim:

| Folder / File Path | Backend Team | Frontend Team | Deskripsi & Batasan Akses |
| --- | --- | --- | --- |
| `app/Http/Controllers/` | **Full Access** | Read Only | Backend mengatur logika bisnis & merender Inertia View |
| `app/Models/` | **Full Access** | No Access | Backend mengelola Eloquent ORM & relasi database |
| `database/migrations/` | **Full Access** | No Access | Backend mengelola skema MariaDB DDL |
| `database/seeders/` | **Full Access** | Read Only | Backend menyiapkan seed data dummy |
| `routes/web.php` | **Full Access** | Read Only | Backend mendaftarkan endpoint rute & RBAC middleware |
| `resources/js/Pages/` | Read Only | **Full Access** | Frontend menyusun tampilan halaman Vue per modul |
| `resources/js/Components/` | No Access | **Full Access** | Frontend membuat re-usable UI (Navbar, Modal, Table) |
| `resources/js/Builder/` | Read Only | **Full Access** | Frontend mengelola Puck Page Builder + Color Picker |
| `resources/css/` | No Access | **Full Access** | Frontend mengelola Tailwind CSS styling |
| `config/` | **Full Access** | No Access | Backend mengelola konfigurasi sistem & RBAC |

---

## 6. Directory Structure (`himatif-nexus`)

```text
himatif-nexus/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Management BPH (Users, Blog)
│   │   │   ├── Infokom/         # Management Formatif Blog, News, & Media
│   │   │   └── Portal/          # Peminjaman Inventaris
│   │   └── Middleware/          # Handle RBAC & Role Checks
│   └── Models/                  # User, InventarisAlat, Penyewaan, Formatif, dll.
├── database/
│   ├── migrations/              # Skema DDL MariaDB
│   └── seeders/                 # Seed Role & Admin Account
├── resources/
│   ├── js/
│   │   ├── Builder/             # Puck Builder Configuration
│   │   │   ├── Components/      # Block Templates (BannerBlock.vue, GridBlock.vue)
│   │   │   ├── Controls/        # Custom Sidebar (SidebarColorControl.vue)
│   │   │   └── puckConfig.js    # Puck Registry Config
│   │   ├── Components/          # Shared Reusable UI Components
│   │   ├── Layouts/             # MainLayout.vue, AdminLayout.vue
│   │   └── Pages/
│   │       ├── Admin/           # Dashboard BPH Management
│   │       ├── Blog/            # Index.vue, Show.vue, Editor.vue (Puck)
│   │       ── Inventory/       # Katalog & Peminjaman Barang
│   └── css/                     # Tailwind styling
└── routes/
    └── web.php                  # Inertia Web Routes + RBAC Middleware

```
