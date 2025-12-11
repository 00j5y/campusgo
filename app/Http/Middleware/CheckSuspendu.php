<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspendu
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. On vérifie si la personne est connectée ET si elle est suspendue
        if (Auth::check() && Auth::user()->est_suspendu == 1) {
            
            // 2. Si oui, on la déconnecte immédiatement
            Auth::logout();

            // 3. On nettoie sa session (sécurité)
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // 4. On la renvoie vers la page de connexion avec un message d'erreur
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été suspendu par un administrateur.',
            ]);
        }

        // Sinon, on laisse passer
        return $next($request);
    }
}