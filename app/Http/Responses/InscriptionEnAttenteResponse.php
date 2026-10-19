<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse;

/**
 * Après une inscription, Fortify connecte le nouvel utilisateur : on le déconnecte aussitôt,
 * car son compte doit d'abord être validé par un administrateur.
 */
class InscriptionEnAttenteResponse implements RegisterResponse
{
    /**
     * @param  Request  $request
     */
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return new JsonResponse(['message' => 'Inscription enregistrée. Un administrateur doit valider votre compte.'], 201);
        }

        return redirect()->route('login')->with('status', 'inscription-en-attente');
    }
}
