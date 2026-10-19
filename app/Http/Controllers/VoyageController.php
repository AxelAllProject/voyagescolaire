<?php

namespace App\Http\Controllers;

use App\Models\Voyage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoyageController extends Controller
{
    /**
     * Display a listing of the voyages.
     */
    public function index(): JsonResponse
    {
        return response()->json(Voyage::all());
    }

    /**
     * Display the specified voyage.
     */
    public function show(Voyage $voyage): JsonResponse
    {
        return response()->json($voyage);
    }

    /**
     * Store a newly created voyage in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'],
            'prix' => ['required', 'numeric', 'min:0'],
            'participation_familiale' => ['required', 'numeric', 'min:0'],
            'nb_places' => ['required', 'integer', 'min:1'],
            'transport' => ['required', 'string', 'max:255'],
        ]);

        $voyage = Voyage::create($validated);

        return response()->json($voyage, 201);
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
