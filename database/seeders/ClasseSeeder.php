<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    /**
     * Seed the school classes.
     */
    public function run(): void
    {
        foreach (['6e A', '6e B', '5e A', '5e B', '4e A', '4e B', '3e A', '3e B'] as $nom) {
            Classe::firstOrCreate(['nom' => $nom]);
        }
    }
}
