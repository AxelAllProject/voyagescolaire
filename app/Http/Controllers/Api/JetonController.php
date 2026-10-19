<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Jetons d'accès à l'API (Sanctum) pour le personnel.
 */
class JetonController extends Controller
{
    /**
     * Exchange the user's credentials for a new API token.
     */
    public function store(Request $request): JsonResponse
    {
        $identifiants = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'appareil' => ['required', 'string', 'max:255'],
        ], [
            'appareil.required' => 'Indiquez un nom pour ce jeton (par exemple « Script d\'import »).',
        ]);

        $user = User::where('email', strtolower($identifiants['email']))->first();

        if (! $user || ! Hash::check($identifiants['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Ces identifiants ne correspondent à aucun compte.',
            ]);
        }

        return response()->json([
            'jeton' => $user->createToken($identifiants['appareil'])->plainTextToken,
            'utilisateur' => $user->only(['id', 'name', 'email', 'role']),
        ], 201);
    }

    /**
     * Revoke the token used for the current request.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(null, 204);
    }
}
