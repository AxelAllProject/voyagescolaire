<?php

namespace Tests\Feature;

use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\User;
use App\Models\Voyage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorisationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_browse_the_public_pages(): void
    {
        $voyage = Voyage::factory()->create(['titre' => 'Découverte de Rome']);

        $this->get(route('accueil'))->assertOk()->assertSee('Espace personnel');
        $this->get(route('voyages.index'))->assertOk()->assertDontSee('Nouveau voyage');
        $this->get(route('voyages.show', $voyage))
            ->assertOk()
            ->assertSee('Découverte de Rome')
            ->assertDontSee(route('voyages.edit', $voyage));
    }

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $voyage = Voyage::factory()->create();

        $this->get(route('voyages.create'))->assertRedirect(route('login'));
        $this->get(route('voyages.edit', $voyage))->assertRedirect(route('login'));
        $this->post(route('voyages.store'))->assertRedirect(route('login'));
        $this->delete(route('voyages.destroy', $voyage))->assertRedirect(route('login'));
        $this->get(route('classes.index'))->assertRedirect(route('login'));
        $this->get(route('pieces-administratives.index'))->assertRedirect(route('login'));
        $this->get(route('compte'))->assertRedirect(route('login'));

        $this->assertModelExists($voyage);
    }

    public function test_staff_can_log_in_and_out(): void
    {
        $user = User::factory()->create(['email' => 'prof@example.com']);

        $this->get(route('login'))->assertOk()->assertSee('Espace personnel');

        $this->post(route('login'), ['email' => 'prof@example.com', 'password' => 'mauvais'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('login'), ['email' => 'prof@example.com', 'password' => 'password'])
            ->assertRedirect('/voyages');
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_there_is_no_public_registration(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_staff_can_create_and_edit_voyages_but_not_delete_them(): void
    {
        $voyage = Voyage::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->get(route('voyages.create'))->assertOk();
        $this->get(route('voyages.edit', $voyage))->assertOk();
        $this->get(route('voyages.show', $voyage))
            ->assertSee(route('voyages.edit', $voyage))
            ->assertDontSee('supprimer-voyage');

        $this->delete(route('voyages.destroy', $voyage))->assertForbidden();
        $this->assertModelExists($voyage);
    }

    public function test_staff_can_only_read_classes_and_pieces(): void
    {
        $classe = Classe::create(['nom' => '3e A']);
        $piece = PieceAdministrative::create(['nom' => 'Fiche sanitaire']);
        $this->actingAs(User::factory()->create());

        $this->get(route('classes.index'))->assertOk()->assertSee('3e A')->assertSee('Consultation seule');
        $this->get(route('pieces-administratives.index'))->assertOk()->assertSee('Fiche sanitaire')->assertSee('Consultation seule');

        $this->post(route('classes.store'), ['nom' => '4e B'])->assertForbidden();
        $this->get(route('classes.edit', $classe))->assertForbidden();
        $this->delete(route('classes.destroy', $classe))->assertForbidden();
        $this->post(route('pieces-administratives.store'), ['nom' => 'Passeport'])->assertForbidden();
        $this->put(route('pieces-administratives.update', $piece), ['nom' => 'Autre'])->assertForbidden();
        $this->delete(route('pieces-administratives.destroy', $piece))->assertForbidden();

        $this->assertDatabaseCount('classes', 1);
        $this->assertDatabaseCount('pieces_administratives', 1);
    }

    public function test_admins_can_delete_voyages(): void
    {
        $voyage = Voyage::factory()->create();
        $this->actingAs(User::factory()->admin()->create());

        $this->delete(route('voyages.destroy', $voyage))->assertRedirect(route('voyages.index'));
        $this->assertModelMissing($voyage);
    }

    public function test_staff_can_change_their_password(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('compte'))->assertOk();

        $this->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'nouveau-mot-de-passe',
            'password_confirmation' => 'nouveau-mot-de-passe',
        ])->assertSessionHas('status', 'password-updated');

        $this->assertTrue(password_verify('nouveau-mot-de-passe', $user->fresh()->password));
    }
}
