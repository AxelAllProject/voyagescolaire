<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Comptes de démonstration (mot de passe : « password »). À ne pas utiliser en production.
        User::factory()->admin()->create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'name' => 'Personnel',
            'email' => 'personnel@example.com',
        ]);

        $this->call([
            ClasseSeeder::class,
            PieceAdministrativeSeeder::class,
            VoyageSeeder::class,
        ]);
    }
}
