<?php

namespace App\Services;

use App\Models\User;

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

    protected array $withRelations = ['role'];

    /**
     * Find user by email instead of primary key.
     */
    public function findByEmail(string $email): ?object
    {
        return app(User::class)->where('email', $email)->first();
    }

    /**
     * Assign a role to a user (RBAC support).
     */
    public function assignRole(string $email, int|string $roleId): bool
    {
        $user = $this->findByEmail($email);

        if (! $user) {
            throw new \InvalidArgumentException("User dengan email {$email} tidak ditemukan");
        }

        return $user->update(['role_id' => $roleId]);
    }

    /**
     * List users filtered by role.
     */
    public function byRole(int|string $roleId)
    {
        return $this->paginate(
            fn (User $q) => $q->where('role_id', $roleId)
        );
    }
}
