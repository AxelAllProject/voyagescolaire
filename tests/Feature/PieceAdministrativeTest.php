<?php

namespace Tests\Feature;

use App\Models\PieceAdministrative;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PieceAdministrativeTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_pieces_are_listed(): void
    {
        PieceAdministrative::create(['nom' => 'Fiche sanitaire', 'description' => 'Allergies et traitements']);

        $this->get(route('pieces-administratives.index'))
            ->assertOk()
            ->assertSee('Fiche sanitaire')
            ->assertSee('Allergies et traitements');
    }

    public function test_a_piece_can_be_added(): void
    {
        $this->post(route('pieces-administratives.store'), ['nom' => 'Autorisation parentale', 'description' => 'Signée'])
            ->assertRedirect(route('pieces-administratives.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pieces_administratives', ['nom' => 'Autorisation parentale']);
    }

    public function test_a_piece_name_is_required(): void
    {
        $this->from(route('pieces-administratives.index'))
            ->post(route('pieces-administratives.store'), ['nom' => ''])
            ->assertSessionHasErrors(['nom' => 'Le nom de la pièce est obligatoire.']);
    }

    public function test_a_piece_can_be_updated_and_deleted(): void
    {
        $piece = PieceAdministrative::create(['nom' => 'Passeport']);

        $this->get(route('pieces-administratives.edit', $piece))->assertOk();

        $this->put(route('pieces-administratives.update', $piece), ['nom' => 'Passeport en cours de validité'])
            ->assertRedirect(route('pieces-administratives.index'));
        $this->assertSame('Passeport en cours de validité', $piece->fresh()->nom);

        $this->delete(route('pieces-administratives.destroy', $piece))
            ->assertRedirect(route('pieces-administratives.index'));
        $this->assertModelMissing($piece);
    }
}
