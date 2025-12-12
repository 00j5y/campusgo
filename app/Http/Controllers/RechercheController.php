<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trajet;
use Carbon\Carbon;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $prenom = $user ? $user->prenom : 'Visiteur';
        
        // Initialisation de la collection vide
        $mesTrajets = collect();

        // Si l'utilisateur est connecté, on récupère ses trajets 
        if ($user) {
            // Trajets conducteur 
            $trajetsConducteur = Trajet::where('id_utilisateur', $user->id_utilisateur)
                ->orderBy('date_depart')
                ->get();
            
            // Trajets passager 
            $trajetsPassager = $user->reservations()
                ->orderBy('date_depart')
                ->get();

            // Fusionner et trier par date
            $mesTrajets = $trajetsConducteur->merge($trajetsPassager)->sortBy('date_depart');
        }

        // Gestion de la Recherche
        $rechercheFaite = false;
        $resultats = collect();

        // On vérifie si une recherche est lancée 
        if ($request->filled('depart') || $request->filled('arrivee')) {
            
            $rechercheFaite = true;
            $villeDepart = $request->input('depart');
            $villeArrivee = $request->input('arrivee');
            $dateDepart = $request->input('date');

            $query = Trajet::query();

            // Recherche flexible 
            if ($villeDepart) {
                $query->where('lieu_depart', 'like', '%' . $villeDepart . '%');
            }

            if ($villeArrivee) {
                $query->where('lieu_arrivee', 'like', '%' . $villeArrivee . '%');
            }

            // Filtre sur la date si elle est fournie
            if ($dateDepart) {
                try {
                    // On parse la date format "d/m/Y" (format flatpickr) vers "Y-m-d" (format SQL)
                    $dateSQL = Carbon::createFromFormat('d/m/Y', $dateDepart)->format('Y-m-d');
                    $query->whereDate('date_depart', $dateSQL);
                } catch (\Exception $e) {
                    // Si format invalide, on ignore la date
                }
            }

            // On récupère uniquement les trajets futurs avec des places
            // Optionnel : ajouter ->where('place_disponible', '>', 0) si tu veux cacher les complets
            $resultats = $query->orderBy('date_depart')->get();
        }

        return view('rechercher', compact('prenom', 'mesTrajets', 'rechercheFaite', 'resultats'));
    }

    // --- 2. FONCTION RESERVER ---
    public function reserver($id)
    {
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user();
        $trajet = Trajet::findOrFail($id);

        // Empêcher de réserver son propre trajet
        if ($trajet->id_utilisateur == $user->id_utilisateur) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas réserver votre propre trajet.');
        }

        // Vérifier si déjà réservé
        if ($user->reservations()->where('trajet.id_trajet', $id)->exists()) {
            return redirect()->back()->with('error', 'Vous avez déjà réservé ce trajet.');
        }

        if ($trajet->place_disponible > 0) {
            $user->reservations()->attach($id);
            $trajet->decrement('place_disponible');
            return redirect()->back()->with('success', 'Trajet réservé avec succès !');
        }

        return redirect()->back()->with('error', 'Désolé, ce trajet est complet.');
    }

    // --- 3. FONCTION ANNULER ---
    public function annuler($id)
    {
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user();
        $trajet = Trajet::findOrFail($id);

        // Cas 1 : C'est le conducteur qui annule (Suppression totale)
        if ($trajet->id_utilisateur == $user->id_utilisateur) {
            // Optionnel : Détacher tous les passagers avant de supprimer si cascade n'est pas configuré en BD
            $trajet->delete();
            return redirect()->back()->with('success', 'Votre trajet a été supprimé.');
        }

        // Cas 2 : C'est un passager qui annule (Désistement)
        // detach() retourne le nombre de lignes supprimées. S'il retourne 0, c'est que l'user n'avait pas réservé.
        $detached = $user->reservations()->detach($id);
        
        if ($detached > 0) {
            $trajet->increment('place_disponible');
            return redirect()->back()->with('success', 'Votre réservation a été annulée.');
        }

        return redirect()->back()->with('error', 'Erreur lors de l\'annulation.');
    }
}