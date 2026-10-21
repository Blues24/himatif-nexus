---
trigger: "@recap"
keywords: ["recap", "documentation", "architecture", "graphify", "summarize", "overview", "summary", "rangkuman"]
paths:
  - "ARCHITECTURE.md"
  - ".ai/rules/**"
  - "README.md"
---

# @recap — Documentation / Graphify Role

## Scope

Documentation role untuk recap, summarization, dan graphify knowledge base project. Trigger ini aktif ketika user ingin:
- Rangkuman arsitektur project
- Overview teknologi yang digunakan
- Dokumentasi ulang struktur project
- Graphify knowledge base

## Files Owned

| File | Purpose |
|---|---|
| `ARCHITECTURE.md` | Primary architecture documentation |
| `.ai/rules/*.md` | AI agent rules dan triggers |
| `README.md` | Project overview |
| `CLAUDE.md` | AI agent instructions |

## How to Use

### Recap Architecture

```text
@recap
"berikan rangkuman arsitektur project ini"
```

AI akan menampilkan:
- Tech stack (PHP 8.5, Laravel 13, Vue 3, Inertia, PostgreSQL)
- Struktur folder MVC + Service Layer
- Database schema overview
- RBAC implementation
- Available roles

### Graphify Knowledge Base

```text
@recap
"ubah semua dokumen menjadi knowledge graph"
```

AI akan:
1. Load semua file di `.ai/rules/`
2. Parse ARCHITECTURE.md sections
3. Generate graph nodes untuk setiap concept
4. Identify relationships antar modules

## Key Information to Extract

### From ARCHITECTURE.md

1. **Tech Stack** (§2)
   - Backend: Laravel 13.35, PHP 8.5
   - Frontend: Vue 3 + Inertia.js
   - Database: PostgreSQL
   - Auth: Laravel Breeze + Sanctum

2. **Database Schema** (§4)
   - 9 tables: users, roles, formatif, kegiatan, transaksi, produk, shop_details, inventaris_alat, penyewaan_alats
   - Primary keys: UUID for users, bigint for others

3. **RBAC** (§8)
   - Custom implementation (NOT Spatie)
   - 4 roles: BPH, Infokom, Portal, Danus
   - Methods: hasRole(), isBph()

4. **Service Layer** (§7)
   - BaseService with CRUD hooks
   - 8 services: Produk, Formatif, Kegiatan, Inventaris, PenyewaanAlat, Transaksi, User

5. **Controllers Status** (§7.8)
   - Implemented: ProdukController, FormatifController, InventarisController, PenyewaanAlatController
   - Stubs: KegiatanController, TransaksiController, Admin\UserController

### From .ai/rules/

1. **Triggers** (§13)
   - @shop, @infokom, @portal, @admin, @frontend, @backend, @recap

2. **Role Files**
   - shop.md: Danus Shop conventions
   - infokom.md: Infokom module
   - portal.md: Portal module
   - admin.md: Admin/BPH management
   - frontend.md: Vue/Tailwind conventions
   - backend.md: MVC/Service Layer conventions

## Graphify Output Format

```
# HIMATIF Nexus Knowledge Graph

## Nodes
- himatif_nexus [project]
- laravel_13 [framework]
- vue_3 [framework]
- inertia [glue]
- postgresql [database]

## Relationships
- himatif_nexus --uses--> laravel_13
- himatif_nexus --uses--> vue_3
- laravel_13 --connects--> inertia
- vue_3 --connects--> inertia
- himatif_nexus --uses--> postgresql
```

## Related Roles

- @backend — untuk backend documentation
- @frontend — untuk frontend documentation