<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trajet;
use Carbon\Carbon;

class RechercheController extends Controller
{
    // Affiche la page et les résultats
    public function index(Request $request)
    {
        // 1. Simulation utilisateur connecté (Marie)
        $user = User::find(1); 
        $prenom = $user ? $user->firstname : 'Visiteur';

        // 2. Récupération de MES trajets (Conducteur + Passager)
        $mesTrajets = Trajet::where('ID_Utilisateur', $user->id)
            ->get()
            ->merge($user->reservations()->get())
            ->sortBy('Date_');

        // 3. Logique de Recherche
        $resultats = null;      
        $rechercheFaite = false; 

        if ($request->filled('depart') || $request->filled('arrivee') || $request->filled('date')) {
            $rechercheFaite = true;
            $query = Trajet::query();
            
            // On exclut nos propres trajets et ceux déjà réservés
            $query->where('ID_Utilisateur', '!=', $user->id)
                  ->whereNotIn('ID_Trajet', $user->reservations()->pluck('reserver.ID_Trajet'));

            if ($request->filled('depart'))  $query->where('Lieu_Depart', 'LIKE', '%' . $request->depart . '%');
            if ($request->filled('arrivee')) $query->where('Lieu_Arrivee', 'LIKE', '%' . $request->arrivee . '%');
            
            if ($request->filled('date')) {
                try {
                    $query->where('Date_', Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d'));
                } catch (\Exception $e) {}
            }
            $resultats = $query->get();
        }

        return view('rechercher', compact('prenom', 'mesTrajets', 'resultats', 'rechercheFaite'));
    }

    // Action : Réserver
    public function reserver($id)
    {
        $user = User::find(1); 
        $trajet = Trajet::findOrFail($id);

        if ($trajet->Place_Disponible > 0) {
            $user->reservations()->attach($id);
            $trajet->decrement('Place_Disponible');
            return redirect()->back()->with('success', 'Trajet réservé avec succès !');
        }
        return redirect()->back()->with('error', 'Désolé, ce trajet est complet.');
    }

    // Action : Annuler
    public function annuler($id)
    {
        $user = User::find(1);
        $trajet = Trajet::findOrFail($id);

        // Si conducteur : supprimer le trajet
        if ($trajet->ID_Utilisateur == $user->id) {
            $trajet->delete();
            return redirect()->back()->with('success', 'Votre trajet a été supprimé.');
        }

        // Si passager : annuler réservation
        $user->reservations()->detach($id);
        $trajet->increment('Place_Disponible');
        return redirect()->back()->with('success', 'Votre réservation a été annulée.');
    }
}