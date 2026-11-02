<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kegiatan>
 */
class KegiatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kegiatan' => $this->faker->sentence(),
            'deskripsi_kegiatan' => $this->faker->paragraph(),
            'tanggal_pelaksanaan' => $this->faker->dateTimeBetween('now', '+1 year'),
            'status_kegiatan' => $this->faker->randomElement(['rencana', 'berjalan', 'selesai']),
        ];
    }
}
