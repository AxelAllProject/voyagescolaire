<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Gestion des classes de l'établissement.
 */
class ClasseController extends Controller
{
    /**
     * Display the classes with the form to add a new one.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Classe::class);

        $classes = Classe::withCount('voyages')->orderBy('nom')->get();

        return view('classes.index', compact('classes'));
    }

    /**
     * Store a newly created classe.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Classe::class);

        $classe = Classe::create($this->validated($request));

        return redirect()
            ->route('classes.index')
            ->with('success', "La classe « {$classe->nom} » a été ajoutée.");
    }

    /**
     * Show the form for editing the classe.
     */
    public function edit(Classe $classe): View
    {
        Gate::authorize('update', $classe);

        return view('classes.edit', compact('classe'));
    }

    /**
     * Update the classe.
     */
    public function update(Request $request, Classe $classe): RedirectResponse
    {
        Gate::authorize('update', $classe);

        $classe->update($this->validated($request, $classe));

        return redirect()
            ->route('classes.index')
            ->with('success', "La classe « {$classe->nom} » a été renommée.");
    }

    /**
     * Remove the classe (it is detached from its voyages).
     */
    public function destroy(Classe $classe): RedirectResponse
    {
        Gate::authorize('delete', $classe);

        $classe->delete();

        return redirect()
            ->route('classes.index')
            ->with('success', "La classe « {$classe->nom} » a été supprimée.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Classe $classe = null): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:50', Rule::unique('classes', 'nom')->ignore($classe)],
        ], [
            'nom.required' => 'Le nom de la classe est obligatoire.',
            'nom.max' => 'Le nom de la classe ne peut pas dépasser 50 caractères.',
            'nom.unique' => 'Cette classe existe déjà.',
        ]);
    }
}
