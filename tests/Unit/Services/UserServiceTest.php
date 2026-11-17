<?php

namespace Tests\Unit\Services;

use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(UserService::class);
    }

    public function test_find_by_email_returns_user(): void
    {
        $email = 'find-email-' . uniqid() . '@example.com';
        $user = User::factory()->create(['email' => $email]);

        $result = $this->service->findByEmail($email);

        $this->assertInstanceOf(User::class, $result);
        $this->assertSame($email, $result->email);
    }

    public function test_find_by_email_returns_null_when_not_found(): void
    {
        $result = $this->service->findByEmail('nonexistent-' . uniqid() . '@example.com');

        $this->assertNull($result);
    }

    public function test_assign_role_updates_user(): void
    {
        $role = Role::factory()->create(['nama_role' => 'BPH-' . uniqid()]);
        $email = 'assign-role-' . uniqid() . '@example.com';
        $user = User::factory()->create(['email' => $email]);

        $result = $this->service->assignRole($email, $role->role_id);

        $this->assertTrue($result);
        $this->assertSame($role->role_id, $user->fresh()->role_id);
    }

    public function test_assign_role_throws_when_user_not_found(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('User dengan email nonexistent@example.com tidak ditemukan');

        $this->service->assignRole('nonexistent@example.com', 1);
    }

    public function test_by_role_filters_users(): void
    {
        $bphRole = Role::factory()->create(['nama_role' => 'BPH-' . uniqid()]);
        $memberRole = Role::factory()->create(['nama_role' => '_anggota-' . uniqid()]);
        $email1 = 'bph-' . uniqid() . '@example.com';
        $email2 = 'bph-' . uniqid() . '@example.com';
        $email3 = 'member-' . uniqid() . '@example.com';

        User::factory()->create(['email' => $email1, 'role_id' => $bphRole->role_id]);
        User::factory()->create(['email' => $email2, 'role_id' => $bphRole->role_id]);
        User::factory()->create(['email' => $email3, 'role_id' => $memberRole->role_id]);

        $result = $this->service->byRole($bphRole->role_id);

        $this->assertCount(2, $result->items());
    }
}
