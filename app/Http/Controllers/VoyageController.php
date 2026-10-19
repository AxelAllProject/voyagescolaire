<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\PieceAdministrative;
use App\Models\Voyage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Contrôleur gérant le cycle de vie des voyages scolaires (Web & API).
 */
class VoyageController extends Controller
{
    /**
     * Retourne la liste complète des voyages scolaires au format JSON.
     */
    public function index(): JsonResponse
    {
        return response()->json(Voyage::all());
    }

    /**
     * Affiche le formulaire Web (Bootstrap 5) de création d'un nouveau voyage scolaire.
     *
     * Charge les classes et les pièces administratives disponibles par ordre alphabétique
     * afin de permettre leur association dès la création du dossier.
     */
    public function create(): View
    {
        $classes = Classe::orderBy('nom')->get();
        $piecesAdministratives = PieceAdministrative::orderBy('nom')->get();

        return view('voyages.create', compact('classes', 'piecesAdministratives'));
    }

    /**
     * Affiche les détails d'un voyage spécifique au format JSON.
     */
    public function show(Voyage $voyage): JsonResponse
    {
        return response()->json($voyage);
    }

    /**
     * Enregistre un nouveau voyage scolaire en base de données.
     *
     * Fonctionnement :
     * 1. Valide les données soumises avec des messages d'erreur personnalisés en français.
     *    En cas d'échec depuis le formulaire Web, Laravel redirige automatiquement vers
     *    le formulaire en conservant les saisies (`old()`) et en injectant `$errors`.
     * 2. Crée l'enregistrement `Voyage` et synchronise les relations Many-to-Many
     *    (`classes` et `piecesAdministratives`).
     * 3. Retourne une réponse JSON (201) pour les appels API ou redirige vers le
     *    formulaire avec un message flash `success` en session pour le Web.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Validation des champs du formulaire et des règles métier
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
            'prix' => ['required', 'numeric', 'min:0'],
            'participation_familiale' => ['required', 'numeric', 'min:0', 'lte:prix'],
            'nb_places' => ['required', 'integer', 'min:1'],
            'transport' => ['required', 'string', 'max:255'],
            'classes' => ['nullable', 'array'],
            'classes.*' => ['integer', 'exists:classes,id'],
            'pieces_administratives' => ['nullable', 'array'],
            'pieces_administratives.*' => ['integer', 'exists:pieces_administratives,id'],
        ], [
            'titre.required' => 'Le titre du voyage est obligatoire.',
            'titre.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description du voyage et de son programme est obligatoire.',
            'image.max' => 'L\'URL ou le chemin de l\'image ne peut pas dépasser 255 caractères.',
            'destination.required' => 'La destination du voyage est obligatoire.',
            'destination.max' => 'La destination ne peut pas dépasser 255 caractères.',
            'date_debut.required' => 'La date de départ est obligatoire.',
            'date_debut.date' => 'Veuillez saisir une date de départ valide.',
            'date_fin.required' => 'La date de retour est obligatoire.',
            'date_fin.date' => 'Veuillez saisir une date de retour valide.',
            'date_fin.after_or_equal' => 'La date de retour doit être postérieure ou égale à la date de départ.',
            'prix.required' => 'Le coût total par élève est obligatoire.',
            'prix.numeric' => 'Le coût total doit être un montant valide.',
            'prix.min' => 'Le coût total ne peut pas être négatif.',
            'participation_familiale.required' => 'La participation familiale est obligatoire.',
            'participation_familiale.numeric' => 'La participation familiale doit être un montant valide.',
            'participation_familiale.min' => 'La participation familiale ne peut pas être négative.',
            'participation_familiale.lte' => 'La participation familiale ne peut pas être supérieure au coût total du voyage.',
            'nb_places.required' => 'Le nombre de places disponibles est obligatoire.',
            'nb_places.integer' => 'Le nombre de places doit être un nombre entier.',
            'nb_places.min' => 'Le voyage doit proposer au moins 1 place.',
            'transport.required' => 'Veuillez sélectionner ou indiquer un moyen de transport.',
            'transport.max' => 'Le moyen de transport ne peut pas dépasser 255 caractères.',
            'classes.array' => 'Le format des classes sélectionnées est invalide.',
            'classes.*.exists' => 'L\'une des classes sélectionnées est introuvable.',
            'pieces_administratives.array' => 'Le format des pièces administratives sélectionnées est invalide.',
            'pieces_administratives.*.exists' => 'L\'une des pièces administratives sélectionnées est introuvable.',
        ]);

        // Création du voyage scolaire
        $voyage = Voyage::create($validated);

        // Association des classes concernées (table pivot classe_voyage)
        if (! empty($validated['classes'])) {
            $voyage->classes()->sync($validated['classes']);
        }

        // Association des pièces administratives requises (table pivot piece_administrative_voyage)
        if (! empty($validated['pieces_administratives'])) {
            $voyage->piecesAdministratives()->sync($validated['pieces_administratives']);
        }

        // Si la requête provient de l'API ou attend du JSON, retourner le code HTTP 201
        if ($request->is('api/*') || $request->expectsJson()) {
            return response()->json($voyage, 201);
        }

        // Redirection Web vers le formulaire avec un message flash de confirmation
        return redirect()
            ->route('voyages.create')
            ->with('success', "Le voyage scolaire « {$voyage->titre} » ({$voyage->destination}) a été enregistré avec succès !");
    }

    /**
     * Update the specified voyage in storage (PUT / PATCH).
     */
    public function update(Request $request, Voyage $voyage): JsonResponse
    {
        $requiredRule = $request->isMethod('put') ? 'required' : 'sometimes';

        $validated = $request->validate([
            'titre' => [$requiredRule, 'string', 'max:255'],
            'description' => [$requiredRule, 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'destination' => [$requiredRule, 'string', 'max:255'],
            'date_debut' => [$requiredRule, 'date'],
            'date_fin' => [$requiredRule, 'date', 'after_or_equal:date_debut'],
            'prix' => [$requiredRule, 'numeric', 'min:0'],
            'participation_familiale' => [$requiredRule, 'numeric', 'min:0'],
            'nb_places' => [$requiredRule, 'integer', 'min:1'],
            'transport' => [$requiredRule, 'string', 'max:255'],
        ]);

        $voyage->update($validated);

        return response()->json($voyage);
    }

    /**
     * Remove the specified voyage from storage.
     */
    public function destroy(Voyage $voyage): JsonResponse
    {
        $voyage->delete();

        return response()->json(null, 204);
    }
}
