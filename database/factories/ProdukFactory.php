<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition(): array
    {
        return [
            'nama_produk' => fake()->words(3, true),
            'deskripsi_produk' => fake()->sentence(),
            'harga_produk' => fake()->randomFloat(2, 1000, 500000),
            'stok_produk' => fake()->numberBetween(10, 100),
            'gambar_produk' => null,
        ];
    }

    public function lowStock(int $stok = 0): static
    {
        return $this->state(fn () => ['stok_produk' => $stok]);
    }
}
