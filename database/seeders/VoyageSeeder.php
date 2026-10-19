<?php

namespace Database\Seeders;

use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\Voyage;
use Illuminate\Database\Seeder;

class VoyageSeeder extends Seeder
{
    /**
     * Seed some upcoming voyages with their classes and pieces administratives.
     */
    public function run(): void
    {
        $voyages = [
            [
                'voyage' => [
                    'titre' => 'Découverte de Rome',
                    'description' => "Visite du Colisée, du Forum romain et du Vatican pour les latinistes de 3e.\nAtelier de mosaïque et rallye photo dans le Trastevere.",
                    'destination' => 'Rome, Italie',
                    'date_debut' => now()->addMonths(2)->format('Y-m-d'),
                    'date_fin' => now()->addMonths(2)->addDays(4)->format('Y-m-d'),
                    'prix' => 520,
                    'participation_familiale' => 320,
                    'nb_places' => 48,
                    'transport' => 'Autocar Grand Tourisme',
                ],
                'classes' => ['3e A', '3e B'],
                'pieces' => ['Autorisation parentale', 'Fiche sanitaire de liaison', 'Carte nationale d\'identité ou passeport', 'Autorisation de sortie du territoire', 'Carte européenne d\'assurance maladie'],
            ],
            [
                'voyage' => [
                    'titre' => 'Séjour linguistique à Londres',
                    'description' => 'Une semaine en famille d\'accueil avec cours d\'anglais le matin et visites l\'après-midi : British Museum, Tower of London et Greenwich.',
                    'destination' => 'Londres, Royaume-Uni',
                    'date_debut' => now()->addMonths(3)->format('Y-m-d'),
                    'date_fin' => now()->addMonths(3)->addDays(6)->format('Y-m-d'),
                    'prix' => 690,
                    'participation_familiale' => 450,
                    'nb_places' => 28,
                    'transport' => 'Train (TGV / Eurostar / Intercités)',
                ],
                'classes' => ['4e A', '4e B'],
                'pieces' => ['Autorisation parentale', 'Fiche sanitaire de liaison', 'Carte nationale d\'identité ou passeport', 'Autorisation de sortie du territoire'],
            ],
            [
                'voyage' => [
                    'titre' => 'Classe de neige à Chamonix',
                    'description' => 'Ski alpin, randonnée en raquettes et découverte du milieu montagnard avec un guide de la vallée.',
                    'destination' => 'Chamonix, France',
                    'date_debut' => now()->addMonths(4)->format('Y-m-d'),
                    'date_fin' => now()->addMonths(4)->addDays(5)->format('Y-m-d'),
                    'prix' => 610,
                    'participation_familiale' => 250,
                    'nb_places' => 52,
                    'transport' => 'Autocar Grand Tourisme',
                ],
                'classes' => ['5e A', '5e B'],
                'pieces' => ['Autorisation parentale', 'Fiche sanitaire de liaison', 'Attestation d\'assurance responsabilité civile'],
            ],
            [
                'voyage' => [
                    'titre' => 'Journée au Futuroscope',
                    'description' => 'Sortie de fin d\'année pour les élèves de 6e : attractions, ateliers scientifiques et spectacle nocturne.',
                    'destination' => 'Poitiers, France',
                    'date_debut' => now()->subMonths(4)->format('Y-m-d'),
                    'date_fin' => now()->subMonths(4)->format('Y-m-d'),
                    'prix' => 75,
                    'participation_familiale' => 40,
                    'nb_places' => 56,
                    'transport' => 'Autocar Grand Tourisme',
                ],
                'classes' => ['6e A', '6e B'],
                'pieces' => ['Autorisation parentale'],
            ],
        ];

        foreach ($voyages as $donnees) {
            $voyage = Voyage::create($donnees['voyage']);
            $voyage->classes()->sync(Classe::whereIn('nom', $donnees['classes'])->pluck('id'));
            $voyage->piecesAdministratives()->sync(PieceAdministrative::whereIn('nom', $donnees['pieces'])->pluck('id'));
        }

        // Voyages générés pour remplir le catalogue (pagination, filtres, statuts).
        Voyage::factory()->count(6)->avecClassesEtPieces()->create();
        Voyage::factory()->enCours()->avecClassesEtPieces(1, 2)->create();
        Voyage::factory()->count(3)->passe()->avecClassesEtPieces()->create();
    }
}
