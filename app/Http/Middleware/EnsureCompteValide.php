<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Refuse l'accès aux comptes qui ne sont pas (ou plus) validés par un administrateur.
 */
class EnsureCompteValide
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->estValide()) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json(['message' => 'Votre compte est en attente de validation par un administrateur.'], 403);
            }

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('status', 'compte-en-attente');
        }

        return $next($request);
    }
}
