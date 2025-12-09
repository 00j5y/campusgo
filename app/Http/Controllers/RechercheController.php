<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trajet;
use Carbon\Carbon;

class RechercheController extends Controller
{
    /**
     * Affiche la page de recherche, les trajets de l'utilisateur et les résultats.
     */
    public function index(Request $request)
    {
        // Simulation de l'utilisateur connecté (Marie - ID 1)
        $user = User::find(1); 
        $prenom = $user ? $user->firstname : 'Visiteur';

        // --- 1. Récupération des trajets personnels (Conducteur & Passager) ---
        $trajetsConducteur = Trajet::where('ID_Utilisateur', $user->id)->get();
        $trajetsPassager = $user->reservations()->get();

        // Fusion des deux collections et tri par date
        $mesTrajets = $trajetsConducteur->merge($trajetsPassager)->sortBy('Date_');

        // --- 2. Logique de Recherche ---
        $resultats = null;      
        $rechercheFaite = false; 

        // Si le formulaire est soumis avec au moins un critère
        if ($request->filled('depart') || $request->filled('arrivee') || $request->filled('date')) {
            $rechercheFaite = true;
            $query = Trajet::query();
            
            // Exclusions :
            // 1. On ne cherche pas nos propres trajets (conducteur)
            $query->where('ID_Utilisateur', '!=', $user->id);
            // 2. On ne cherche pas les trajets qu'on a déjà réservés
            $idsReserves = $user->reservations()->pluck('reserver.ID_Trajet');
            $query->whereNotIn('ID_Trajet', $idsReserves);

            // Application des filtres
            if ($request->filled('depart')) {
                $query->where('Lieu_Depart', 'LIKE', '%' . $request->depart . '%');
            }
            if ($request->filled('arrivee')) {
                $query->where('Lieu_Arrivee', 'LIKE', '%' . $request->arrivee . '%');
            }
            if ($request->filled('date')) {
                try {
                    // Conversion format FR (d/m/Y) vers SQL (Y-m-d)
                    $date = Carbon::createFromFormat('d/m/Y', $request->date)->format('Y-m-d');
                    $query->where('Date_', $date);
                } catch (\Exception $e) {
                    // Date invalide ignorée
                }
            }
            $resultats = $query->get();
        }

        return view('rechercher', compact('prenom', 'mesTrajets', 'resultats', 'rechercheFaite'));
    }

    /**
     * Traite la réservation d'un trajet.
     */
    public function reserver($id)
    {
        $user = User::find(1); 
        $trajet = Trajet::findOrFail($id);

        if ($trajet->Place_Disponible > 0) {
            $user->reservations()->attach($id); // Ajout dans la table pivot
            $trajet->decrement('Place_Disponible'); // -1 place

            return redirect()->back()->with('success', 'Trajet réservé avec succès !');
        }

        return redirect()->back()->with('error', 'Désolé, ce trajet est complet.');
    }

    /**
     * Traite l'annulation (Suppression si conducteur, Désinscription si passager).
     */
    public function annuler($id)
    {
        $user = User::find(1);
        $trajet = Trajet::findOrFail($id);

        // Cas Conducteur : Suppression définitive du trajet
        if ($trajet->ID_Utilisateur == $user->id) {
            $trajet->delete();
            return redirect()->back()->with('success', 'Votre trajet a été supprimé.');
        }

        // Cas Passager : Annulation de la réservation
        $user->reservations()->detach($id); // Suppression de la table pivot
        $trajet->increment('Place_Disponible'); // +1 place

        return redirect()->back()->with('success', 'Votre réservation a été annulée.');
    }
}