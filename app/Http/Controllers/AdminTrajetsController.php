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
        // Vérification que l'utilisateur est admin
        if (Auth::user()->est_admin != 1) {
            return redirect('/');
        }

        // Récupération de tous les trajets
        $trajets = Trajet::all(); 

        // Statistiques pour le tableau de bord
        $stats = [
            'users' => User::count(),
            'trajets' => Trajet::count(), 
        ];

        // Retourner la vue
        return view('admintrajets', compact('trajets', 'stats'));
    }

    public function destroy($id){

        // Vérification que l'utilisateur est admin
        if (Auth::user()->est_admin != 1) {
            return redirect('/');
        }

        // Suppression du trajet
        $trajet = Trajet::findOrFail($id);
        $trajet->delete();

        // Confirmation de la suppression
        return back()->with('success', 'Le trajet a bien été supprimé.');

    }   
}