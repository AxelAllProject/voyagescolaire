<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VoyageRequest;
use App\Models\Voyage;
use Illuminate\Http\JsonResponse;

/**
 * API JSON des voyages scolaires (routes préfixées par /api, noms api.voyages.*).
 */
class VoyageController extends Controller
{
    /**
     * Display a listing of the voyages.
     */
    public function index(): JsonResponse
    {
        return response()->json(Voyage::orderBy('date_debut')->get());
    }

    /**
     * Store a newly created voyage, with its classes and pieces administratives.
     */
    public function store(VoyageRequest $request): JsonResponse
    {
        $voyage = Voyage::create($request->voyageData());
        $voyage->syncRelations($request->validated());

        return response()->json($voyage->load('classes', 'piecesAdministratives'), 201);
    }

    /**
     * Display the specified voyage with its relations.
     */
    public function show(Voyage $voyage): JsonResponse
    {
        return response()->json($voyage->load('classes', 'piecesAdministratives'));
    }

    /**
     * Update the specified voyage (PUT replaces every field, PATCH only the given ones).
     */
    public function update(VoyageRequest $request, Voyage $voyage): JsonResponse
    {
        $voyage->update($request->voyageData());
        $voyage->syncRelations($request->validated());

        return response()->json($voyage->load('classes', 'piecesAdministratives'));
    }

    /**
     * Remove the specified voyage.
     */
    public function destroy(Voyage $voyage): JsonResponse
    {
        $voyage->delete();

        return response()->json(null, 204);
    }
}
