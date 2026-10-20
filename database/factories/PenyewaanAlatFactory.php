<?php

namespace Database\Factories;

use App\Models\Inventaris;
use App\Models\PenyewaanAlat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenyewaanAlat>
 */
class PenyewaanAlatFactory extends Factory
{
    protected $model = PenyewaanAlat::class;

    public function definition(): array
    {
        return [
            'inventaris_alat_id' => Inventaris::factory(),
            'user_id' => User::factory(),
            'jumlah_pinjam' => fake()->numberBetween(1, 5),
            'tanggal_pinjam' => fake()->dateTimeBetween('-1 week', 'now'),
            'tanggal_kembali' => fake()->dateTimeBetween('now', '+2 weeks'),
            'status' => 'pending',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function disetujui(): static
    {
        return $this->state(fn () => ['status' => 'disetujui']);
    }

    public function ditolak(): static
    {
        return $this->state(fn () => ['status' => 'ditolak']);
    }

    public function dikembalikan(): static
    {
        return $this->state(fn () => ['status' => 'dikembalikan']);
    }
}
