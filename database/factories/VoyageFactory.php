<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\Voyage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Voyage>
 */
class VoyageFactory extends Factory
{
    /**
     * Destinations réalistes (reconnues par le globe 3D de l'accueil) et thème du séjour.
     *
     * @var array<string, string>
     */
    private const DESTINATIONS = [
        'Rome, Italie' => 'Découverte de la Rome antique',
        'Londres, Royaume-Uni' => 'Séjour linguistique à Londres',
        'Berlin, Allemagne' => 'Berlin, mémoire et histoire',
        'Madrid, Espagne' => 'Immersion hispanique à Madrid',
        'Barcelone, Espagne' => 'Gaudí et Barcelone moderniste',
        'Amsterdam, Pays-Bas' => 'Amsterdam au fil des canaux',
        'Bruxelles, Belgique' => 'Bruxelles et les institutions européennes',
        'Athènes, Grèce' => 'Sur les traces de la Grèce antique',
        'Florence, Italie' => 'Florence, berceau de la Renaissance',
        'Édimbourg, Royaume-Uni' => 'Châteaux et légendes d\'Écosse',
        'Chamonix, France' => 'Classe de neige à Chamonix',
        'Strasbourg, France' => 'Strasbourg, capitale européenne',
        'Marseille, France' => 'Classe de mer à Marseille',
    ];

    /**
     * Mêmes choix que la liste déroulante du formulaire.
     *
     * @var list<string>
     */
    private const TRANSPORTS = [
        'Autocar Grand Tourisme',
        'Train (TGV / Eurostar / Intercités)',
        'Avion (Vol régulier)',
        'Ferry + Autocar',
        'Transports en commun / Mixte',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $destination = fake()->randomKey(self::DESTINATIONS);
        $dateDebut = fake()->dateTimeBetween('+1 month', '+6 months');
        $prix = fake()->randomFloat(2, 100, 1500);

        return [
            'titre' => self::DESTINATIONS[$destination],
            'description' => fake()->paragraphs(2, true),
            'image' => null,
            'destination' => $destination,
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_fin' => (clone $dateDebut)->modify('+'.fake()->numberBetween(1, 7).' days')->format('Y-m-d'),
            'prix' => $prix,
            // Toujours inférieure au prix, comme l'exige la validation.
            'participation_familiale' => round($prix * fake()->randomFloat(2, 0.3, 0.8), 2),
            'nb_places' => fake()->numberBetween(20, 60),
            'transport' => fake()->randomElement(self::TRANSPORTS),
        ];
    }

    /**
     * A voyage that is already over.
     */
    public function passe(): static
    {
        return $this->state(function (array $attributes) {
            $dateDebut = fake()->dateTimeBetween('-1 year', '-1 month');

            return [
                'date_debut' => $dateDebut->format('Y-m-d'),
                'date_fin' => (clone $dateDebut)->modify('+'.fake()->numberBetween(1, 5).' days')->format('Y-m-d'),
            ];
        });
    }

    /**
     * A voyage happening right now.
     */
    public function enCours(): static
    {
        return $this->state(fn (array $attributes) => [
            'date_debut' => now()->subDay()->format('Y-m-d'),
            'date_fin' => now()->addDays(3)->format('Y-m-d'),
        ]);
    }

    /**
     * Attach some existing classes and pieces administratives after creation.
     */
    public function avecClassesEtPieces(int $classes = 2, int $pieces = 3): static
    {
        return $this->afterCreating(function (Voyage $voyage) use ($classes, $pieces) {
            $voyage->classes()->attach(Classe::inRandomOrder()->limit($classes)->pluck('id'));
            $voyage->piecesAdministratives()->attach(PieceAdministrative::inRandomOrder()->limit($pieces)->pluck('id'));
        });
    }
}
