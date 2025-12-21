<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trajet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RechercheController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $prenom = $user ? $user->prenom : 'Visiteur';

        $mesTrajets = collect();

        // Trajets de l'utilisateur connecté
        if ($user) {
            $idsReservations = DB::table('reserver')
                ->where('id_utilisateur', $user->id)
                ->pluck('id_trajet')
                ->toArray();

            $mesTrajets = Trajet::with('conducteur') 
                ->where(function ($query) use ($user, $idsReservations) {
                    $query->where('id_utilisateur', $user->id)
                          ->orWhereIn('id', $idsReservations);
                })
                ->where(function ($query) {
                    $query->where('date_depart', '>', Carbon::now()->toDateString())
                          ->orWhere(function ($q) {
                              $q->where('date_depart', Carbon::now()->toDateString())
                                ->where('heure_depart', '>', Carbon::now()->toTimeString());
                          });
                })
                ->orderBy('date_depart')
                ->orderBy('heure_depart')
                ->get();
        }

        $rechercheFaite = false;
        $resultats = collect();

        // Lancement de la recherche
        if ($request->filled('depart') || $request->filled('arrivee')) {
            $rechercheFaite = true;

            $villeDepart = $request->input('depart');
            $villeArrivee = $request->input('arrivee');
            $dateDepart = $request->input('date');

            $query = Trajet::query();

            if ($villeDepart) {
                $query->where('lieu_depart', 'like', '%' . $villeDepart . '%');
            }

            if ($villeArrivee) {
                $query->where('lieu_arrivee', 'like', '%' . $villeArrivee . '%');
            }

            if ($dateDepart) {
                try {
                    $dateSQL = Carbon::createFromFormat('d/m/Y', $dateDepart)
                        ->format('Y-m-d');
                    $query->whereDate('date_depart', $dateSQL);
                } catch (\Exception $e) {
                    // date invalide ignorée
                }
            }

            $resultats = $query->with('conducteur')->orderBy('date_depart')->get();
        }

        return view('rechercher', compact(
            'prenom',
            'mesTrajets',
            'rechercheFaite',
            'resultats'
        ));
    }

    public function reserver($id)
    {
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user();
        $trajet = Trajet::findOrFail($id);

        // Interdire la réservation de son propre trajet
        if ($trajet->id_utilisateur == $user->id) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas réserver votre propre trajet.');
        }

        // Vérifier réservation existante
        if ($user->reservations()->where('trajet.id', $id)->exists()) {
            return redirect()->back()
                ->with('error', 'Vous avez déjà réservé ce trajet.');
        }

        if ($trajet->place_disponible > 0) {
            $user->reservations()->attach($id);
            $trajet->decrement('place_disponible');

            return redirect()->back()
                ->with('success', 'Trajet réservé avec succès !');
        }

        return redirect()->back()
            ->with('error', 'Désolé, ce trajet est complet.');
    }

    public function annuler($id)
    {
        if (!Auth::check()) return redirect('/login');

        $user = Auth::user();
        $trajet = Trajet::findOrFail($id);

        // Annulation par le conducteur
        if ($trajet->id_utilisateur == $user->id) {
            $trajet->delete();
            return redirect()->back()
                ->with('success', 'Votre trajet a été supprimé.');
        }

        // Annulation par un passager
        $detached = $user->reservations()->detach($id);

        if ($detached > 0) {
            $trajet->increment('place_disponible');
            return redirect()->back()
                ->with('success', 'Votre réservation a été annulée.');
        }

        return redirect()->back()
            ->with('error', 'Erreur lors de l\'annulation.');
    }
}
