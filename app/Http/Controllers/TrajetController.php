<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trajet; // Assure-toi que ton Modèle s'appelle bien Trajet
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrajetController extends Controller
{
    public function mesTrajets()
    {
    $userId = Auth::id(); 

    // 1. On récupère d'abord les ID des trajets où l'utilisateur est PASSAGER (table reserver)
    $idsReservations = DB::table('reserver')
                         ->where('id_utilisateur', $userId)
                         ->pluck('id_trajet')
                         ->toArray();

    // 2. Récupérer les trajets À VENIR
    // La logique est : (Je suis Conducteur OU Je suis Passager) ET (La date est future)
    $trajetsAvenir = Trajet::where(function($query) use ($userId, $idsReservations) {
            $query->where('id_utilisateur', $userId)               // Cas Conducteur
                  ->orWhereIn('id_trajet', $idsReservations);      // Cas Passager
        })
        ->where(function($query) {
            $query->where('date_depart', '>', Carbon::now()->toDateString())
                  ->orWhere(function($q) {
                      $q->where('date_depart', '=', Carbon::now()->toDateString())
                        ->where('heure_depart', '>', Carbon::now()->toTimeString());
                  });
        })
        ->orderBy('date_depart', 'asc')
        ->orderBy('heure_depart', 'asc')
        ->get();

    // 3. Récupérer les trajets PASSÉS (Même logique)
    $trajetsPasses = Trajet::where(function($query) use ($userId, $idsReservations) {
            $query->where('id_utilisateur', $userId)               // Cas Conducteur
                  ->orWhereIn('id_trajet', $idsReservations);      // Cas Passager
        })
        ->where(function($query) {
            $query->where('date_depart', '<', Carbon::now()->toDateString())
                  ->orWhere(function($q) {
                      $q->where('date_depart', '=', Carbon::now()->toDateString())
                        ->where('heure_depart', '<', Carbon::now()->toTimeString());
                  });
        })
        ->orderBy('date_depart', 'desc')
        ->orderBy('heure_depart', 'desc')
        ->get();

    return view('mes-trajets', compact('trajetsAvenir', 'trajetsPasses'));
}
}