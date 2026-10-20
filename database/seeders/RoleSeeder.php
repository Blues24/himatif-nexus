<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /** @var array<string> */
    public const ROLES = ['BPH', 'Infokom', 'Portal', 'Danus Shop'];

    public function run(): void
    {
        foreach (self::ROLES as $name) {
            Role::query()->firstOrCreate(['nama_role' => $name]);
        }
    }
}
