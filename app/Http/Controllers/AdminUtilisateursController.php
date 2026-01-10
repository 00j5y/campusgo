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
        // Verification que l'utilisateur est admin
        if (Auth::user()->est_admin != 1) {
            return redirect('/');
        }

        // Récupérer les utilisateurs avec pages de 10
        $users = User::withCount('trajets')
            ->withAvg('avisRecus as note_moyenne', 'note')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistiques pour le tableau de bord
        $stats = [
            'users' => User::count(),
            'trajets' => Trajet::count(),
        ];

        // Retourner la vue
        return view('adminutilisateurs', compact('users', 'stats'));

    }

    public function destroy($id){

        // Vérification que l'utilisateur est admin
        if (Auth::user()->est_admin != 1) {
            return redirect('/');
        }

        // Recherche de l'utilisateur
        $user = User::findOrFail($id);

        // Erreur si destruction de soi-même
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Suppression de l'utilisateur
        $user->delete();

        // Retour à la liste
        return back()->with('success', 'Utilisateur supprimé définitivement.');
    }


    public function toggleSuspend($id){

        // Vérification que l'utilisateur est admin
        if (Auth::user()->est_admin != 1) {
            return redirect('/');
        }

        // Recherche de l'utilisateur
        $user = User::findOrFail($id);

        // Erreur si suspension de soi-même
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte.');
        }

        // Inversion de l'état de suspension
        $user->est_suspendu = ! $user->est_suspendu;
        $user->save();

        // Confirmation de suspension/réactivation
        $etat = $user->est_suspendu ? 'suspendu' : 'réactivé';
        return back()->with('success', "Le compte a bien été $etat.");
    }

}
