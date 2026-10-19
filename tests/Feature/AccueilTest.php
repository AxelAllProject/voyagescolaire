<?php

namespace Tests\Feature;

use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccueilTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_is_displayed(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Organisez les voyages de vos classes')
            ->assertSee('Aucun voyage prévu pour le moment');
    }

    public function test_the_home_page_lists_upcoming_voyages_only(): void
    {
        Voyage::factory()->create(['titre' => 'Voyage à venir', 'date_debut' => now()->addMonth(), 'date_fin' => now()->addMonth()->addDays(3)]);
        Voyage::factory()->create(['titre' => 'Voyage passé', 'date_debut' => now()->subMonth(), 'date_fin' => now()->subMonth()->addDays(3)]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Voyage à venir')
            ->assertDontSee('Voyage passé');
    }
}
