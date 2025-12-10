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
}