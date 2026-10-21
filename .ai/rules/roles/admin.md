---
trigger: "@admin"
keywords: ["admin", "bph", "user", "role", "permission", "access", "management", "user_uuid"]
paths:
  - "app/Http/Controllers/Admin/**"
  - "app/Services/UserService.php"
  - "app/Models/User.php"
  - "app/Models/Role.php"
  - "resources/js/Pages/Admin/**"
---

# @admin — Admin/BPH Role

## Scope

Admin/BPH module mengelola user management, role assignment, dan akses kontrol untuk seluruh sistem.

### Files Owned

| File | Purpose |
|---|---|
| `app/Http/Controllers/Admin/UserController.php` | ⚠️ Stub — needs implementation |
| `app/Services/UserService.php` | User management & RBAC assignment |
| `app/Models/User.php` | User model with RBAC methods |
| `app/Models/Role.php` | Role definition model |
| `resources/js/Pages/Admin/Users.vue` | User management (planned) |
| `resources/js/Pages/Admin/Roles.vue` | Role management (planned) |

## RBAC Implementation

### Custom RBAC (NOT Spatie)

Sistem menggunakan custom RBAC tanpa package Spatie:

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

### Available Roles

| Role | Description | Access Level |
|---|---|---|
| `BPH` | Badan Pengurus Harian | Full access to all modules |
| `Infokom` | Informasi & Komunikasi | Content management (Formatif, Kegiatan) |
| `Portal` | Portal Management | Inventory & equipment loans |
| `Danus` | Dana Usaha | Shop & product management |

### User Model Details

```php
class User extends Authenticatable
{
    protected $table = 'users';
    protected $primaryKey = 'user_uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_uuid',
        'role_id',
        'nama',
        'name', // Alias for 'nama' (Breeze compatibility)
        'email',
        'password',
    ];

    // nama <-> name bridge for Breeze compatibility
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }
}
```

## Database Schema

### `users` Table

| Column | Type | Description |
|---|---|---|
| `user_uuid` | uuid (PK) | Primary key (UUID) |
| `role_id` | bigint (FK) | Reference to roles |
| `nama` | string | Display name |
| `email` | string (unique) | Email address |
| `email_verified_at` | timestamp (nullable) | Verification timestamp |
| `password` | string | Hashed password |
| `remember_token` | string (nullable) | Remember me token |
| `timestamps` | timestamp | created_at, updated_at |

### `roles` Table

| Column | Type | Description |
|---|---|---|
| `role_id` | bigint (PK) | Auto-increment |
| `nama_role` | string (unique) | Role name (BPH, Infokom, Portal, Danus) |

## Common Tasks

### Create User with Role

```php
// In UserService
public function create(array $data): User
{
    $data['user_uuid'] = Str::uuid();
    $data['password'] = Hash::make($data['password']);
    
    return parent::create($data);
}

public function assignRole(User $user, string $roleName): void
{
    $role = Role::where('nama_role', $roleName)->firstOrFail();
    $user->update(['role_id' => $role->role_id]);
}
```

### Check User Permission

```php
// In controller or service
if ($user->isBph()) {
    // BPH-only logic
}

if ($user->hasRole('Infokom')) {
    // Infokom team access
}
```

### Middleware (Planned)

```php
// Future implementation
Route::middleware('role:BPH')->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
});
```

## UserService Implementation

```php
class UserService extends BaseService
{
    protected string $model = User::class;
    protected array $fillableColumns = [
        'user_uuid',
        'role_id',
        'nama',
        'email',
        'password',
    ];

    public function create(array $data): User
    {
        $data['user_uuid'] = Str::uuid();
        $data['password'] = Hash::make($data['password']);
        
        return DB::transaction(function () use ($data) {
            return app($this->model)->create($data);
        });
    }

    public function byRole(string $roleName): Collection
    {
        return $this->model::whereHas('role', function ($query) use ($roleName) {
            $query->where('nama_role', $roleName);
        })->get();
    }
}
```

## Frontend Pages (Planned)

### Admin/Users.vue

- User listing with role badges
- Add/Edit user modal
- Role assignment dropdown
- Search by name/email

### Admin/Roles.vue

- Role overview
- Permission matrix (future)
- Role assignment statistics

## Related Roles

- `@shop` — Danus role for shop management
- `@infokom` — Infokom role for content management
- `@portal` — Portal role for inventory management
- `@backend` — RBAC middleware implementation