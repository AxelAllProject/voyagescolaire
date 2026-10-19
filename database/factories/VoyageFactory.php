<?php

namespace Database\Factories;

use App\Models\Voyage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Voyage>
 */
class VoyageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateDebut = fake()->dateTimeBetween('+1 month', '+6 months');

        return [
            'titre' => 'Voyage à '.fake()->city(),
            'description' => fake()->paragraph(),
            'image' => null,
            'destination' => fake()->city(),
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => (clone $dateDebut)->modify('+'.fake()->numberBetween(1, 10).' days')->format('Y-m-d'),
            'prix' => fake()->randomFloat(2, 100, 1500),
            'participation_familiale' => fake()->randomFloat(2, 50, 500),
            'nb_places' => fake()->numberBetween(10, 60),
            'transport' => fake()->randomElement(['car', 'train', 'avion']),
        ];
    }
}
