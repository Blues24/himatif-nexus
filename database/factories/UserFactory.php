<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_uuid' => fake()->uuid(),
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => fake()->md5(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function bph(): static
    {
        return $this->state(fn () => ['role_id' => Role::firstOrCreate(['nama_role' => 'BPH'])->role_id]);
    }

    public function member(): static
    {
        return $this->state(fn () => ['role_id' => Role::firstOrCreate(['nama_role' => '_anggota'])->role_id]);
    }
}
