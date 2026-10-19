<?php

namespace Database\Factories;

use App\Models\PieceAdministrative;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PieceAdministrative>
 */
class PieceAdministrativeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => 'Document '.fake()->unique()->words(3, true),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
