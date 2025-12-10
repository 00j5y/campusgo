<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trajet;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminTrajetsController extends Controller
{
    public function index()
    {
        // 1. Sécurité : Vérifie que l'utilisateur est admin
        if (Auth::user()->est_admin != 1) {
            return redirect('/');
        }

        // 2. Récupérer tous les trajets pour ton tableau
        $trajets = Trajet::all(); 

        // 3. Les Stats pour le Layout (OBLIGATOIRE)
        $stats = [
            'users' => User::count(),
            'trajets' => Trajet::count(), 
        ];

        // 4. Retourner la vue
        return view('admintrajets', compact('trajets', 'stats'));
    }
}