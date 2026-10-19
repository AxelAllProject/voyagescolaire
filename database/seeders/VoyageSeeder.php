<?php

namespace Database\Seeders;

use App\Models\Voyage;
use Illuminate\Database\Seeder;

class VoyageSeeder extends Seeder
{
    /**
     * Seed some upcoming voyages for the home page.
     */
    public function run(): void
    {
        Voyage::factory()->createMany([
            [
                'titre' => 'Découverte de Rome',
                'description' => 'Visite du Colisée, du Forum romain et du Vatican pour les latinistes de 3e.',
                'destination' => 'Rome',
                'date_debut' => now()->addMonths(2)->format('Y-m-d'),
                'date_fin' => now()->addMonths(2)->addDays(4)->format('Y-m-d'),
                'prix' => 520,
                'participation_familiale' => 200,
                'nb_places' => 35,
                'transport' => 'car',
            ],
            [
                'titre' => 'Séjour linguistique à Londres',
                'description' => 'Une semaine en famille d\'accueil avec cours d\'anglais le matin et visites l\'après-midi.',
                'destination' => 'Londres',
                'date_debut' => now()->addMonths(3)->format('Y-m-d'),
                'date_fin' => now()->addMonths(3)->addDays(6)->format('Y-m-d'),
                'prix' => 690,
                'participation_familiale' => 320,
                'nb_places' => 28,
                'transport' => 'train',
            ],
            [
                'titre' => 'Classe de neige à Chamonix',
                'description' => 'Ski, randonnée en raquettes et découverte du milieu montagnard pour les élèves de 5e.',
                'destination' => 'Chamonix',
                'date_debut' => now()->addMonths(4)->format('Y-m-d'),
                'date_fin' => now()->addMonths(4)->addDays(5)->format('Y-m-d'),
                'prix' => 610,
                'participation_familiale' => 250,
                'nb_places' => 48,
                'transport' => 'car',
            ],
        ]);
    }
}
