<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoyageRequest;
use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\Voyage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Pages web des voyages scolaires : catalogue, fiche détaillée, création, modification et suppression.
 */
class VoyageController extends Controller
{
    /**
     * Display the voyages catalogue, filtered by period and search terms.
     */
    public function index(Request $request): View
    {
        $periode = $request->query('periode', 'a-venir');
        $recherche = trim((string) $request->query('q', ''));

        $voyages = Voyage::query()
            ->withCount('classes')
            ->when($periode === 'a-venir', fn ($query) => $query->whereDate('date_fin', '>=', today())->orderBy('date_debut'))
            ->when($periode === 'passes', fn ($query) => $query->whereDate('date_fin', '<', today())->orderByDesc('date_debut'))
            ->when($periode === 'tous', fn ($query) => $query->orderByDesc('date_debut'))
            ->when($recherche !== '', function ($query) use ($recherche) {
                $query->where(function ($query) use ($recherche) {
                    $query->where('titre', 'like', "%{$recherche}%")
                        ->orWhere('destination', 'like', "%{$recherche}%");
                });
            })
            ->paginate(9)
            ->withQueryString();

        return view('voyages.index', compact('voyages', 'periode', 'recherche'));
    }

    /**
     * Show the form for creating a new voyage.
     */
    public function create(): View
    {
        Gate::authorize('create', Voyage::class);

        return view('voyages.create', [
            'voyage' => new Voyage,
            ...$this->formOptions(),
        ]);
    }

    /**
     * Store a newly created voyage and its relations.
     */
    public function store(VoyageRequest $request): RedirectResponse
    {
        $voyage = Voyage::create($request->voyageData());
        $voyage->syncRelations($request->validated());

        return redirect()
            ->route('voyages.show', $voyage)
            ->with('success', "Le voyage « {$voyage->titre} » a été créé.");
    }

    /**
     * Display the voyage details.
     */
    public function show(Voyage $voyage): View
    {
        $voyage->load([
            'classes' => fn ($query) => $query->orderBy('nom'),
            'piecesAdministratives' => fn ($query) => $query->orderBy('nom'),
        ]);

        return view('voyages.show', compact('voyage'));
    }

    /**
     * Show the form for editing the voyage.
     */
    public function edit(Voyage $voyage): View
    {
        Gate::authorize('update', $voyage);

        $voyage->load('classes', 'piecesAdministratives');

        return view('voyages.edit', [
            'voyage' => $voyage,
            ...$this->formOptions(),
        ]);
    }

    /**
     * Update the voyage and its relations.
     */
    public function update(VoyageRequest $request, Voyage $voyage): RedirectResponse
    {
        $voyage->update($request->voyageData());

        // Une case décochée n'est pas envoyée : on vide les relations absentes du formulaire.
        $voyage->syncRelations([
            'classes' => $request->validated('classes', []),
            'pieces_administratives' => $request->validated('pieces_administratives', []),
        ]);

        return redirect()
            ->route('voyages.show', $voyage)
            ->with('success', "Le voyage « {$voyage->titre} » a été mis à jour.");
    }

    /**
     * Remove the voyage.
     */
    public function destroy(Voyage $voyage): RedirectResponse
    {
        Gate::authorize('delete', $voyage);

        $voyage->delete();

        return redirect()
            ->route('voyages.index')
            ->with('success', "Le voyage « {$voyage->titre} » a été supprimé.");
    }

    /**
     * Classes and pieces administratives offered in the voyage form.
     *
     * @return array<string, mixed>
     */
    private function formOptions(): array
    {
        return [
            'classes' => Classe::orderBy('nom')->get(),
            'piecesAdministratives' => PieceAdministrative::orderBy('nom')->get(),
        ];
    }
}
