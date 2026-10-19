<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\User;
use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoyageWebTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_the_catalogue_lists_upcoming_voyages_by_default(): void
    {
        Voyage::factory()->create(['titre' => 'Voyage à venir', 'date_debut' => now()->addMonth(), 'date_fin' => now()->addMonth()->addDays(2)]);
        Voyage::factory()->create(['titre' => 'Voyage terminé', 'date_debut' => now()->subMonth(), 'date_fin' => now()->subMonth()->addDays(2)]);

        $this->get(route('voyages.index'))
            ->assertOk()
            ->assertSee('Voyage à venir')
            ->assertDontSee('Voyage terminé');

        $this->get(route('voyages.index', ['periode' => 'passes']))
            ->assertOk()
            ->assertSee('Voyage terminé')
            ->assertDontSee('Voyage à venir');
    }

    public function test_the_catalogue_can_be_searched(): void
    {
        Voyage::factory()->create(['titre' => 'Découverte de Rome', 'destination' => 'Rome', 'date_debut' => now()->addMonth(), 'date_fin' => now()->addMonth()]);
        Voyage::factory()->create(['titre' => 'Séjour à Berlin', 'destination' => 'Berlin', 'date_debut' => now()->addMonth(), 'date_fin' => now()->addMonth()]);

        $this->get(route('voyages.index', ['q' => 'Rome']))
            ->assertOk()
            ->assertSee('Découverte de Rome')
            ->assertDontSee('Séjour à Berlin');
    }

    public function test_the_voyage_page_shows_its_details_and_relations(): void
    {
        $voyage = Voyage::factory()->create(['titre' => 'Classe de neige', 'prix' => 600, 'participation_familiale' => 250]);
        $voyage->classes()->attach(Classe::create(['nom' => '5e A']));
        $voyage->piecesAdministratives()->attach(PieceAdministrative::create(['nom' => 'Fiche sanitaire']));

        $this->get(route('voyages.show', $voyage))
            ->assertOk()
            ->assertSee('Classe de neige')
            ->assertSee('5e A')
            ->assertSee('Fiche sanitaire')
            ->assertSee('350,00 €');
    }

    public function test_the_edit_form_is_prefilled(): void
    {
        $classe = Classe::create(['nom' => '4e B']);
        $voyage = Voyage::factory()->create(['titre' => 'Séjour à Londres']);
        $voyage->classes()->attach($classe);

        $this->get(route('voyages.edit', $voyage))
            ->assertOk()
            ->assertSee('value="Séjour à Londres"', false)
            ->assertSee('value="'.$classe->id.'" class="form-check-input mt-1" checked', false);
    }

    public function test_a_voyage_can_be_updated_and_its_classes_unchecked(): void
    {
        $voyage = Voyage::factory()->create();
        $voyage->classes()->attach(Classe::create(['nom' => '6e A']));

        $data = $voyage->only(['titre', 'description', 'destination', 'prix', 'participation_familiale', 'nb_places', 'transport']);

        $this->put(route('voyages.update', $voyage), [
            ...$data,
            'titre' => 'Nouveau titre',
            'date_debut' => $voyage->date_debut->format('Y-m-d'),
            'date_fin' => $voyage->date_fin->format('Y-m-d'),
        ])
            ->assertRedirect(route('voyages.show', $voyage))
            ->assertSessionHas('success');

        $this->assertSame('Nouveau titre', $voyage->fresh()->titre);
        $this->assertCount(0, $voyage->fresh()->classes);
    }

    public function test_a_voyage_can_be_deleted(): void
    {
        $voyage = Voyage::factory()->create();

        $this->delete(route('voyages.destroy', $voyage))
            ->assertRedirect(route('voyages.index'))
            ->assertSessionHas('success');

        $this->assertModelMissing($voyage);
    }
}
