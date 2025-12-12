<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trajet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrajetController extends Controller
{
    public function mesTrajets()
    {
        $userId = Auth::id();

        // ID des trajets réservés en tant que passager
        $idsReservations = DB::table('reserver')
            ->where('id_utilisateur', $userId)
            ->pluck('id_trajet')
            ->toArray();

        // Trajets à venir (conducteur ou passager)
        $trajetsAvenir = Trajet::where(function ($query) use ($userId, $idsReservations) {
                $query->where('id_utilisateur', $userId)
                      ->orWhereIn('id_trajet', $idsReservations);
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

        // Trajets passés (conducteur ou passager)
        $trajetsPasses = Trajet::where(function ($query) use ($userId, $idsReservations) {
                $query->where('id_utilisateur', $userId)
                      ->orWhereIn('id_trajet', $idsReservations);
            })
            ->where(function ($query) {
                $query->where('date_depart', '<', Carbon::now()->toDateString())
                      ->orWhere(function ($q) {
                          $q->where('date_depart', Carbon::now()->toDateString())
                            ->where('heure_depart', '<', Carbon::now()->toTimeString());
                      });
            })
            ->orderBy('date_depart', 'desc')
            ->orderBy('heure_depart', 'desc')
            ->get();

        return view('mes-trajets', compact('trajetsAvenir', 'trajetsPasses'));
    }
}
