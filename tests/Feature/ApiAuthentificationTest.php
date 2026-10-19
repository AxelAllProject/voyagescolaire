<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiAuthentificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reading_voyages_does_not_need_a_token(): void
    {
        $voyage = Voyage::factory()->create();

        $this->getJson('/api/voyages')->assertOk();
        $this->getJson("/api/voyages/{$voyage->id}")->assertOk();
    }

    public function test_writing_needs_a_token(): void
    {
        $voyage = Voyage::factory()->create();

        $this->postJson('/api/voyages', [])->assertUnauthorized()
            ->assertJsonPath('message', 'Non authentifié. Envoyez un jeton valide dans l\'en-tête Authorization: Bearer.');
        $this->patchJson("/api/voyages/{$voyage->id}", ['nb_places' => 3])->assertUnauthorized();
        $this->deleteJson("/api/voyages/{$voyage->id}")->assertUnauthorized();

        $this->assertModelExists($voyage);
    }

    public function test_a_token_can_be_obtained_and_used(): void
    {
        User::factory()->create(['email' => 'prof@example.com']);

        $jeton = $this->postJson('/api/jetons', ['email' => 'prof@example.com', 'password' => 'password', 'appareil' => 'Tests'])
            ->assertCreated()
            ->assertJsonPath('utilisateur.email', 'prof@example.com')
            ->json('jeton');

        $voyage = Voyage::factory()->create();

        $this->withToken($jeton)
            ->patchJson("/api/voyages/{$voyage->id}", ['nb_places' => 12])
            ->assertOk()
            ->assertJsonPath('nb_places', 12);
    }

    public function test_a_token_is_refused_with_wrong_credentials(): void
    {
        User::factory()->create(['email' => 'prof@example.com']);

        $this->postJson('/api/jetons', ['email' => 'prof@example.com', 'password' => 'mauvais', 'appareil' => 'Tests'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.']);
    }

    public function test_a_token_can_be_revoked(): void
    {
        $user = User::factory()->create();
        $jeton = $user->createToken('Tests')->plainTextToken;

        $this->withToken($jeton)->deleteJson('/api/jetons/actuel')->assertNoContent();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_staff_tokens_cannot_delete_voyages(): void
    {
        $voyage = Voyage::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson("/api/voyages/{$voyage->id}")
            ->assertForbidden()
            ->assertJsonPath('message', 'Cette action n\'est pas autorisée.');

        $this->assertModelExists($voyage);
    }
}
