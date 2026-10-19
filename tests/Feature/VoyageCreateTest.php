<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoyageCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_voyage_form_is_accessible(): void
    {
        $response = $this->get(route('voyages.create'));

        $response->assertStatus(200);
        $response->assertSee('Créer un nouveau voyage scolaire');
    }

    public function test_can_create_voyage_with_flash_message(): void
    {
        $classe = Classe::create(['nom' => '3ème A']);
        $piece = PieceAdministrative::create([
            'nom' => 'Carte Nationale d\'Identité',
            'description' => 'En cours de validité',
        ]);

        $payload = [
            'titre' => 'Séjour culturel à Rome',
            'description' => 'Découverte de la Rome antique : Colisée, Forum Romain et Vatican.',
            'image' => 'https://example.com/rome.jpg',
            'destination' => 'Rome, Italie',
            'date_debut' => '2027-04-10',
            'date_fin' => '2027-04-15',
            'prix' => 450.00,
            'participation_familiale' => 320.00,
            'nb_places' => 48,
            'transport' => 'Autocar Grand Tourisme',
            'classes' => [$classe->id],
            'pieces_administratives' => [$piece->id],
        ];

        $response = $this->post(route('voyages.store'), $payload);

        $response->assertRedirect(route('voyages.show', Voyage::first()));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('voyages', [
            'titre' => 'Séjour culturel à Rome',
            'destination' => 'Rome, Italie',
            'nb_places' => 48,
        ]);

        $voyage = Voyage::first();
        $this->assertTrue($voyage->classes->contains($classe));
        $this->assertTrue($voyage->piecesAdministratives->contains($piece));
    }

    public function test_validation_errors_and_old_input_are_preserved(): void
    {
        $invalidPayload = [
            'titre' => 'Voyage test erreur',
            'description' => '', // required error
            'destination' => 'Londres, Royaume-Uni',
            'date_debut' => '2027-05-20',
            'date_fin' => '2027-05-15', // before date_debut error
            'prix' => 300,
            'participation_familiale' => 400, // > prix error
            'nb_places' => 0, // min:1 error
            'transport' => '', // required error
        ];

        $response = $this->from(route('voyages.create'))
            ->post(route('voyages.store'), $invalidPayload);

        $response->assertRedirect(route('voyages.create'));
        $response->assertSessionHasErrors([
            'description',
            'date_fin',
            'participation_familiale',
            'nb_places',
            'transport',
        ]);

        // Verify old input is preserved in session
        $this->assertEquals('Voyage test erreur', session()->getOldInput('titre'));
        $this->assertEquals('Londres, Royaume-Uni', session()->getOldInput('destination'));
    }
}
