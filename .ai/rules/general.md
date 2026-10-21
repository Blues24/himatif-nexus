---
paths:
  - '**/*'
---

# General

## MANDATORY: Read ARCHITECTURE.md on every prompt
CRITICAL: Before responding to ANY prompt or starting ANY task, you MUST read ARCHITECTURE.md first. This is non-negotiable.

ARCHITECTURE.md contains:
- Current tech stack (PHP 8.5, Laravel 13.35, Inertia v2, Vue 3)
- Database schema with actual table names (singular: kegiatan, transaksi, produk, inventaris_alat, penyewaan_alats)
- RBAC: Custom implementation (NOT Spatie) with User::role(), hasRole(), isBph()
- Controller status: ProdukController✅, FormatifController✅, InventarisController✅, PenyewaanAlatController✅, KegiatanController⚠️stub, TransaksiController⚠️stub, UserController⚠️stub
- Service Layer pattern: Controller → Service → Model
- Known issues: None confirmed. All migrations and models are consistent on table names.

Never assume anything without verifying against ARCHITECTURE.md first. If ARCHITECTURE.md conflicts with your training, follow ARCHITECTURE.md.

## Role Trigger Detection

When you detect a trigger keyword in the user's prompt, you MUST:

1. **Read the corresponding role file** in `.ai/rules/roles/`
2. **Load the context** for that specific module
3. **Follow the conventions** defined in that role file

### Trigger Keywords

| Trigger | Role | Keywords |
|---|---|---|
| `@shop` | Danus Shop | produk, shop, danus, stok, harga, keranjang, transaksi |
| `@infokom` | Infokom | formatif, blog, kegiatan, artikel, puck |
| `@portal` | Portal | inventaris, peminjaman, alat, pinjam, kembali, approve, reject |
| `@admin` | Admin/BPH | admin, bph, user, role, permission, access, management |
| `@frontend` | Frontend Team | vue, component, tailwind, css, ui, layout, page, form |
| `@backend` | Backend Team | controller, service, model, migration, route, request, database |
| `@recap` | Documentation / Graphify | architecture, documentation, recap, graphify, summarize |

### Intelligent Detection

If the user doesn't explicitly use a trigger keyword, auto-detect from:

1. **File path** being edited — if path contains `DanusSHop`, use `@shop` context
2. **Keywords** in the prompt — if prompt mentions "stok" or "harga", use `@shop` context
3. **Git branch** — if branch name is `feature/shop-xxx`, use `@shop` context

### Example Usage

```
User: "@shop tambah fitur diskon produk"
AI: ✅ Load .ai/rules/roles/shop.md
     → Follow ProdukController conventions
     → Use ProdukService for business logic
     → Follow Shop frontend page structure

User: "buat approve button di inventory"
AI: ✅ Detect keyword "approve" → auto-trigger @portal
     → Load .ai/rules/roles/portal.md
     → Follow PenyewaanAlatController conventions
```

## Service Layer Enforcement

When generating or editing backend code:

1. **Controllers must be thin** — only handle HTTP, validation, and redirect
2. **Business logic goes to Services** — always delegate to `$this->service->...`
3. **Validation in Form Requests** — never validate directly in controller
4. **Use deferred props** — `fn () =>` for expensive queries in Inertia responses
5. **Wrap in transactions** — use `DB::transaction()` for multi-step operations
