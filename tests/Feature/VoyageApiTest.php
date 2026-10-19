<?php

namespace Tests\Feature;

use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoyageApiTest extends TestCase
{
    use RefreshDatabase;

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
                'titre' => 'Le champ titre est obligatoire.',
                'date_debut' => 'Le champ date de début est obligatoire.',
                'participation_familiale' => 'Le champ participation familiale est obligatoire.',
                'nb_places' => 'Le champ nombre de places est obligatoire.',
            ]);
    }

    public function test_it_rejects_an_end_date_before_the_start_date(): void
    {
        $this->postJson('/api/voyages', $this->payload(['date_fin' => '2027-02-01']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['date_fin' => 'Le champ date de fin doit être une date postérieure ou égale au date de début.']);
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
