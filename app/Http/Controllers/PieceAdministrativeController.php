<?php

namespace App\Http\Controllers;

use App\Models\PieceAdministrative;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Gestion des pièces administratives demandées aux familles.
 */
class PieceAdministrativeController extends Controller
{
    /**
     * Display the pieces administratives with the form to add a new one.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', PieceAdministrative::class);

        $pieces = PieceAdministrative::withCount('voyages')->orderBy('nom')->get();

        return view('pieces-administratives.index', compact('pieces'));
    }

    /**
     * Store a newly created piece administrative.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', PieceAdministrative::class);

        $piece = PieceAdministrative::create($this->validated($request));

        return redirect()
            ->route('pieces-administratives.index')
            ->with('success', "La pièce « {$piece->nom} » a été ajoutée.");
    }

    /**
     * Show the form for editing the piece administrative.
     */
    public function edit(PieceAdministrative $piece): View
    {
        Gate::authorize('update', $piece);

        return view('pieces-administratives.edit', compact('piece'));
    }

    /**
     * Update the piece administrative.
     */
    public function update(Request $request, PieceAdministrative $piece): RedirectResponse
    {
        Gate::authorize('update', $piece);

        $piece->update($this->validated($request, $piece));

        return redirect()
            ->route('pieces-administratives.index')
            ->with('success', "La pièce « {$piece->nom} » a été mise à jour.");
    }

    /**
     * Remove the piece administrative (it is detached from its voyages).
     */
    public function destroy(PieceAdministrative $piece): RedirectResponse
    {
        Gate::authorize('delete', $piece);

        $piece->delete();

        return redirect()
            ->route('pieces-administratives.index')
            ->with('success', "La pièce « {$piece->nom} » a été supprimée.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?PieceAdministrative $piece = null): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:255', Rule::unique('pieces_administratives', 'nom')->ignore($piece)],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'nom.required' => 'Le nom de la pièce est obligatoire.',
            'nom.max' => 'Le nom de la pièce ne peut pas dépasser 255 caractères.',
            'nom.unique' => 'Cette pièce administrative existe déjà.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
        ]);
    }
}
