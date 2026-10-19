<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Gestion des comptes par les administrateurs : validation des inscriptions, rôles et suppression.
 */
class CompteController extends Controller
{
    /**
     * Display the pending registrations first, then the approved accounts.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        return view('comptes.index', [
            'enAttente' => User::enAttente()->latest()->get(),
            'valides' => User::whereNotNull('valide_le')->orderBy('name')->get(),
        ]);
    }

    /**
     * Approve a pending registration.
     */
    public function valider(User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user->valider();

        return redirect()
            ->route('comptes.index')
            ->with('success', "Le compte de {$user->name} est validé : la personne peut maintenant se connecter.");
    }

    /**
     * Change the role of an account.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $donnees = $request->validate([
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        $user->update($donnees);

        return redirect()
            ->route('comptes.index')
            ->with('success', "{$user->name} est maintenant « {$user->role->libelle()} ».");
    }

    /**
     * Delete an account, or refuse a pending registration.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        $etaitEnAttente = ! $user->estValide();
        $user->tokens()->delete();
        $user->delete();

        return redirect()
            ->route('comptes.index')
            ->with('success', $etaitEnAttente
                ? "L'inscription de {$user->name} a été refusée."
                : "Le compte de {$user->name} a été supprimé.");
    }
}
