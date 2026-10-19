<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Nombre de classes affiché sur les cartes des voyages (issue #9).
 */
class VoyageCardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Three upcoming voyages with 0, 1 and 2 classes.
     */
    private function voyagesAvecClasses(): void
    {
        [$a, $b] = [Classe::create(['nom' => '3e A']), Classe::create(['nom' => '3e B'])];

        Voyage::factory()->create(['titre' => 'Sans classe', 'nb_places' => 30, 'transport' => 'Car', 'date_debut' => now()->addDays(10), 'date_fin' => now()->addDays(12)]);
        Voyage::factory()->create(['titre' => 'Une classe', 'nb_places' => 30, 'transport' => 'Car', 'date_debut' => now()->addDays(20), 'date_fin' => now()->addDays(22)])
            ->classes()->attach($a);
        Voyage::factory()->create(['titre' => 'Deux classes', 'nb_places' => 30, 'transport' => 'Car', 'date_debut' => now()->addDays(30), 'date_fin' => now()->addDays(32)])
            ->classes()->attach([$a->id, $b->id]);
    }

    public function test_the_catalogue_cards_show_the_number_of_classes(): void
    {
        $this->voyagesAvecClasses();

        $this->get(route('voyages.index'))
            ->assertOk()
            ->assertSeeInOrder(['Sans classe', 'bi-mortarboard', 'Aucune classe', '30 places · Car'])
            ->assertSeeInOrder(['Une classe', 'bi-mortarboard', '1 classe', '30 places · Car'])
            ->assertSeeInOrder(['Deux classes', 'bi-mortarboard', '2 classes', '30 places · Car']);
    }

    public function test_the_home_page_cards_show_the_number_of_classes(): void
    {
        $this->voyagesAvecClasses();

        $this->get(route('accueil'))
            ->assertOk()
            ->assertSeeInOrder(['Sans classe', 'bi-mortarboard', 'Aucune classe', '30 places · Car'])
            ->assertSeeInOrder(['Une classe', 'bi-mortarboard', '1 classe', '30 places · Car'])
            ->assertSeeInOrder(['Deux classes', 'bi-mortarboard', '2 classes', '30 places · Car']);
    }

    public function test_the_cards_do_not_run_one_query_per_voyage(): void
    {
        $compterRequetes = function (string $url): int {
            DB::flushQueryLog();
            DB::enableQueryLog();
            $this->get($url)->assertOk();
            DB::disableQueryLog();

            return count(DB::getQueryLog());
        };

        Voyage::factory()->create(['date_debut' => now()->addDays(5), 'date_fin' => now()->addDays(6)]);
        $avecUnVoyage = ['accueil' => $compterRequetes(route('accueil')), 'catalogue' => $compterRequetes(route('voyages.index'))];

        Voyage::factory()->count(2)->create(['date_debut' => now()->addDays(5), 'date_fin' => now()->addDays(6)])
            ->each(fn (Voyage $voyage) => $voyage->classes()->attach(Classe::create(['nom' => 'Classe '.$voyage->id])));

        $this->assertSame($avecUnVoyage['accueil'], $compterRequetes(route('accueil')));
        $this->assertSame($avecUnVoyage['catalogue'], $compterRequetes(route('voyages.index')));
    }
}
