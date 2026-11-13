<?php

namespace Database\Factories;

use App\Models\Formatif;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Formatif>
 */
class FormatifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    : array
    {
        return [
            'user_id' => User::factory(),
            'judul' => fake()->sentence(),
            'slug' => fake()->slug(),
            'kategori' => fake()->randomElement(['Tutorial', 'Artikel', 'Modul']),
            'text_konten' => fake()->paragraphs(3, true),
            'status' => 'published',
        ];
    }
}
