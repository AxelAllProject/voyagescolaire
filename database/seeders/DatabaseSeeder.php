<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with demonstration data (php artisan migrate:fresh --seed).
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClasseSeeder::class,
            PieceAdministrativeSeeder::class,
            VoyageSeeder::class,
        ]);
    }
}
