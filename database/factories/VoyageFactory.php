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
        $prix = fake()->randomFloat(2, 100, 1500);

        return [
            'titre' => 'Voyage à '.fake()->city(),
            'description' => fake()->paragraph(),
            'image' => null,
            'destination' => fake()->city(),
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => (clone $dateDebut)->modify('+'.fake()->numberBetween(1, 10).' days')->format('Y-m-d'),
            'prix' => $prix,
            // Toujours inférieure au prix, comme l'exige la validation.
            'participation_familiale' => round($prix * fake()->randomFloat(2, 0.3, 0.8), 2),
            'nb_places' => fake()->numberBetween(10, 60),
            'transport' => fake()->randomElement(['car', 'train', 'avion']),
        ];
    }
}
