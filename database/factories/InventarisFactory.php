<?php

namespace Database\Factories;

use App\Models\Inventaris;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inventaris>
 */
class InventarisFactory extends Factory
{
    protected $model = Inventaris::class;

    public function definition(): array
    {
        return [
            'nama_barang' => fake()->words(3, true),
            'stok' => fake()->numberBetween(1, 20),
            'kondisi' => fake()->randomElement(['baik', 'rusak', 'hilang']),
        ];
    }

    public function baik(): static
    {
        return $this->state(fn () => ['kondisi' => 'baik', 'stok' => fake()->numberBetween(10, 20)]);
    }

    public function rusak(): static
    {
        return $this->state(fn () => ['kondisi' => 'rusak']);
    }

    public function hilang(): static
    {
        return $this->state(fn () => ['kondisi' => 'hilang', 'stok' => 0]);
    }

    public function lowStock(int $stok = 2): static
    {
        return $this->state(fn () => ['stok' => $stok, 'kondisi' => 'baik']);
    }
}
