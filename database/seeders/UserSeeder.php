<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Demonstration accounts (password: "password"). Never use them in production.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Administrateur',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'name' => 'Personnel',
            'email' => 'personnel@example.com',
        ]);

        // Inscriptions en attente, à valider depuis la page Comptes.
        User::factory()->enAttente()->createMany([
            ['name' => 'Claire Martin', 'email' => 'claire.martin@example.com'],
            ['name' => 'Karim Benali', 'email' => 'karim.benali@example.com'],
        ]);
    }
}
