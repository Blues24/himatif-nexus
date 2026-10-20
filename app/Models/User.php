<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'user_uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    /** @var array<string> */
    protected $fillable = [
        'user_uuid',
        'role_id',
        'nama',
        'name',
        'email',
        'password',
    ];

    /** @var array<string> */
    protected $appends = ['name'];

    /** @var array<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAuthIdentifierName(): string
    {
        return 'user_uuid';
    }

    /**
     * The users table stores the display name in `nama`. Breeze scaffolding,
     * its factories and its Vue pages all read and write `name`, so the two are
     * bridged here instead of renaming the column.
     */
    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute(?string $value): void
    {
        $this->attributes['nama'] = $value;
    }

    /** @return BelongsTo<Role,self> */
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
}
