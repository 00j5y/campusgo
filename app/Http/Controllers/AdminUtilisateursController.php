<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Trajet;
use Illuminate\Support\Facades\Auth; 

class AdminUtilisateursController extends Controller
{
    public function index()
    {
        // 1. La sécurité
        if (Auth::user()->est_admin != 1) {
            return redirect('/');
        }

        // 2. Récupérer les utilisateurs pour le tableau
        $users = User::all();

        // 3. Récupérer les statistiques
        $stats = [
            'users' => User::count(),    // Compte tous les inscrits
            'trajets' => Trajet::count() // Compte tous les trajets
        ];

        // 4. Retourner la vue en envoyant $users ET $stats
        return view('adminutilisateurs', compact('users', 'stats'));
    }

    public function destroy($id)
{
    // 1. Sécurité
    if (Auth::user()->est_admin != 1) {
        return redirect('/');
    }

    // 2. On trouve l'utilisateur
    $user = User::findOrFail($id);

    // 3. Sécurité : On empêche de se supprimer soi-même !
    if ($user->id === Auth::id()) {
        return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
    }

    // 4. On supprime
    $user->delete();

    // 5. On revient à la liste
    return back()->with('success', 'Utilisateur supprimé définitivement.');
}



}

