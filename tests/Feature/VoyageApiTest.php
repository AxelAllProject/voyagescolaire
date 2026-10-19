<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\User;
use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class VoyageApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(User::factory()->admin()->create());
    }

    /**
     * A valid payload for creating a voyage.
     *
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'titre' => 'Découverte de Rome',
            'description' => 'Visite du Colisée et du Vatican.',
            'destination' => 'Rome',
            'date_debut' => '2027-03-01',
            'date_fin' => '2027-03-05',
            'prix' => 520.50,
            'participation_familiale' => 200,
            'nb_places' => 35,
            'transport' => 'car',
        ], $overrides);
    }

    public function test_it_lists_voyages(): void
    {
        Voyage::factory()->count(3)->create();

        $this->getJson('/api/voyages')
            ->assertOk()
            ->assertJsonCount(3);
    }

    public function test_it_shows_a_voyage(): void
    {
        $voyage = Voyage::factory()->create(['titre' => 'Séjour à Londres']);

        $this->getJson("/api/voyages/{$voyage->id}")
            ->assertOk()
            ->assertJsonPath('id', $voyage->id)
            ->assertJsonPath('titre', 'Séjour à Londres');
    }

    public function test_it_returns_a_french_message_when_a_voyage_is_not_found(): void
    {
        $this->getJson('/api/voyages/999')
            ->assertNotFound()
            ->assertExactJson(['message' => 'Ressource introuvable.']);
    }

    public function test_it_returns_a_french_message_for_an_unsupported_method(): void
    {
        $voyage = Voyage::factory()->create();

        $this->postJson("/api/voyages/{$voyage->id}")
            ->assertMethodNotAllowed()
            ->assertExactJson(['message' => 'Méthode non autorisée']);
    }

    public function test_it_creates_a_voyage(): void
    {
        $this->postJson('/api/voyages', $this->payload())
            ->assertCreated()
            ->assertJsonPath('titre', 'Découverte de Rome');

        $this->assertDatabaseHas('voyages', ['titre' => 'Découverte de Rome', 'nb_places' => 35]);
    }

    public function test_it_returns_french_validation_messages_when_creating(): void
    {
        $this->postJson('/api/voyages', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'titre' => 'Le titre du voyage est obligatoire.',
                'date_debut' => 'La date de départ est obligatoire.',
                'participation_familiale' => 'La participation familiale est obligatoire.',
                'nb_places' => 'Le nombre de places disponibles est obligatoire.',
            ]);
    }

    public function test_it_rejects_an_end_date_before_the_start_date(): void
    {
        $this->postJson('/api/voyages', $this->payload(['date_fin' => '2027-02-01']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['date_fin' => 'La date de retour doit être postérieure ou égale à la date de départ.']);
    }

    public function test_it_rejects_a_family_participation_above_the_price(): void
    {
        $this->postJson('/api/voyages', $this->payload(['prix' => 300, 'participation_familiale' => 400]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['participation_familiale' => 'La participation familiale ne peut pas être supérieure au coût total du voyage.']);
    }

    public function test_patch_compares_the_participation_with_the_stored_price(): void
    {
        $voyage = Voyage::factory()->create(['prix' => 300, 'participation_familiale' => 100]);

        $this->patchJson("/api/voyages/{$voyage->id}", ['participation_familiale' => 250])->assertOk();
        $this->patchJson("/api/voyages/{$voyage->id}", ['participation_familiale' => 350])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('participation_familiale');
    }

    public function test_it_creates_a_voyage_with_its_classes_and_pieces(): void
    {
        $classe = Classe::create(['nom' => '3e A']);
        $piece = PieceAdministrative::create(['nom' => 'Autorisation parentale']);

        $this->postJson('/api/voyages', $this->payload(['classes' => [$classe->id], 'pieces_administratives' => [$piece->id]]))
            ->assertCreated()
            ->assertJsonPath('classes.0.nom', '3e A')
            ->assertJsonPath('pieces_administratives.0.nom', 'Autorisation parentale');
    }

    public function test_put_requires_every_field(): void
    {
        $voyage = Voyage::factory()->create();

        $this->putJson("/api/voyages/{$voyage->id}", ['titre' => 'Nouveau titre'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['destination', 'prix', 'nb_places']);
    }

    public function test_put_replaces_a_voyage(): void
    {
        $voyage = Voyage::factory()->create();

        $this->putJson("/api/voyages/{$voyage->id}", $this->payload())
            ->assertOk()
            ->assertJsonPath('destination', 'Rome');
    }

    public function test_patch_updates_only_the_given_fields(): void
    {
        $voyage = Voyage::factory()->create(['destination' => 'Berlin']);

        $this->patchJson("/api/voyages/{$voyage->id}", ['nb_places' => 12])
            ->assertOk()
            ->assertJsonPath('nb_places', 12)
            ->assertJsonPath('destination', 'Berlin');
    }

    public function test_it_deletes_a_voyage(): void
    {
        $voyage = Voyage::factory()->create();

        $this->deleteJson("/api/voyages/{$voyage->id}")->assertNoContent();

        $this->assertModelMissing($voyage);
    }
}
