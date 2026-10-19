<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InscriptionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function formulaire(array $surcharge = []): array
    {
        return array_merge([
            'name' => 'Claire Martin',
            'email' => 'claire.martin@college.fr',
            'password' => 'mot-de-passe-solide',
            'password_confirmation' => 'mot-de-passe-solide',
        ], $surcharge);
    }

    public function test_the_registration_page_is_displayed(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Créer un compte')
            ->assertSee('validé par un administrateur', false);
    }

    public function test_registering_creates_a_pending_staff_account_and_logs_out(): void
    {
        $this->post(route('register'), $this->formulaire())
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'inscription-en-attente');

        $this->assertGuest();

        $user = User::firstWhere('email', 'claire.martin@college.fr');
        $this->assertSame(Role::Personnel, $user->role);
        $this->assertFalse($user->estValide());
    }

    public function test_registration_cannot_choose_its_role_or_approve_itself(): void
    {
        $this->post(route('register'), $this->formulaire(['role' => 'admin', 'valide_le' => now()->toDateTimeString()]));

        $user = User::firstWhere('email', 'claire.martin@college.fr');
        $this->assertSame(Role::Personnel, $user->role);
        $this->assertNull($user->valide_le);
    }

    public function test_registration_is_validated_in_french(): void
    {
        User::factory()->create(['email' => 'claire.martin@college.fr']);

        $this->post(route('register'), $this->formulaire(['password_confirmation' => 'autre']))
            ->assertSessionHasErrors([
                'email' => 'Un compte existe déjà avec cette adresse email.',
                'password',
            ]);
    }

    public function test_a_pending_account_cannot_log_in(): void
    {
        User::factory()->enAttente()->create(['email' => 'claire.martin@college.fr']);

        $this->post(route('login'), ['email' => 'claire.martin@college.fr', 'password' => 'password'])
            ->assertSessionHasErrors(['email' => 'Votre compte est en attente de validation par un administrateur.']);

        $this->assertGuest();
    }

    public function test_a_pending_account_cannot_get_an_api_token(): void
    {
        User::factory()->enAttente()->create(['email' => 'claire.martin@college.fr']);

        $this->postJson('/api/jetons', ['email' => 'claire.martin@college.fr', 'password' => 'password', 'appareil' => 'Tests'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email' => 'Votre compte est en attente de validation par un administrateur.']);
    }

    public function test_an_account_that_is_no_longer_approved_is_logged_out(): void
    {
        $user = User::factory()->enAttente()->create();

        $this->actingAs($user)
            ->get(route('voyages.create'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'compte-en-attente');

        $this->assertGuest();

        Sanctum::actingAs($user);
        $this->postJson('/api/voyages', [])->assertForbidden();
    }

    public function test_an_admin_can_approve_a_registration_which_can_then_log_in(): void
    {
        $compte = User::factory()->enAttente()->create(['email' => 'claire.martin@college.fr']);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('comptes.index'))
            ->assertOk()
            ->assertSee('claire.martin@college.fr');

        $this->patch(route('comptes.valider', $compte))
            ->assertRedirect(route('comptes.index'))
            ->assertSessionHas('success');
        $this->assertTrue($compte->fresh()->estValide());

        $this->post(route('logout'));
        $this->post(route('login'), ['email' => 'claire.martin@college.fr', 'password' => 'password']);
        $this->assertAuthenticatedAs($compte);
    }

    public function test_an_admin_can_refuse_a_registration_and_change_roles(): void
    {
        $enAttente = User::factory()->enAttente()->create();
        $personnel = User::factory()->create();
        $this->actingAs(User::factory()->admin()->create());

        $this->delete(route('comptes.destroy', $enAttente))->assertRedirect(route('comptes.index'));
        $this->assertModelMissing($enAttente);

        $this->put(route('comptes.update', $personnel), ['role' => 'admin'])->assertRedirect(route('comptes.index'));
        $this->assertSame(Role::Admin, $personnel->fresh()->role);

        $this->put(route('comptes.update', $personnel), ['role' => 'super-admin'])->assertSessionHasErrors('role');
    }

    public function test_an_admin_cannot_demote_or_delete_themselves(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->put(route('comptes.update', $admin), ['role' => 'personnel'])->assertForbidden();
        $this->delete(route('comptes.destroy', $admin))->assertForbidden();

        $this->assertSame(Role::Admin, $admin->fresh()->role);
    }

    public function test_staff_cannot_manage_accounts(): void
    {
        $compte = User::factory()->enAttente()->create();
        $this->actingAs(User::factory()->create());

        $this->get(route('comptes.index'))->assertForbidden();
        $this->patch(route('comptes.valider', $compte))->assertForbidden();
        $this->assertFalse($compte->fresh()->estValide());
    }

    public function test_admins_see_the_number_of_pending_registrations(): void
    {
        User::factory()->enAttente()->count(2)->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('voyages.index'))
            ->assertSee('title="Inscriptions en attente">2</span>', false);
    }
}
