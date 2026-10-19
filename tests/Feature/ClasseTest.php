<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClasseTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_classes_are_listed(): void
    {
        Classe::create(['nom' => '3e A']);

        $this->get(route('classes.index'))
            ->assertOk()
            ->assertSee('3e A');
    }

    public function test_a_classe_can_be_added(): void
    {
        $this->post(route('classes.store'), ['nom' => '4e C'])
            ->assertRedirect(route('classes.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('classes', ['nom' => '4e C']);
    }

    public function test_a_classe_name_must_be_unique(): void
    {
        Classe::create(['nom' => '4e C']);

        $this->from(route('classes.index'))
            ->post(route('classes.store'), ['nom' => '4e C'])
            ->assertRedirect(route('classes.index'))
            ->assertSessionHasErrors(['nom' => 'Cette classe existe déjà.']);
    }

    public function test_a_classe_can_be_renamed(): void
    {
        $classe = Classe::create(['nom' => '4e C']);

        $this->get(route('classes.edit', $classe))->assertOk();

        $this->put(route('classes.update', $classe), ['nom' => '4e D'])
            ->assertRedirect(route('classes.index'));

        $this->assertSame('4e D', $classe->fresh()->nom);
    }

    public function test_deleting_a_classe_detaches_it_from_its_voyages(): void
    {
        $classe = Classe::create(['nom' => '4e C']);
        $voyage = Voyage::factory()->create();
        $voyage->classes()->attach($classe);

        $this->delete(route('classes.destroy', $classe))
            ->assertRedirect(route('classes.index'));

        $this->assertModelMissing($classe);
        $this->assertModelExists($voyage);
        $this->assertDatabaseCount('classe_voyage', 0);
    }
}
