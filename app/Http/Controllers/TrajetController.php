<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Trajet; 
use App\Models\Vehicule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TrajetController extends Controller
{
    //Récupération
    public function create()
    {
        //Si l'utilisateur n'est pas connecté, il est redirigé
        if (!Auth::check()) {
            return redirect()->route('login'); 
        }

        //Une fois l'utilisateur connecté , on récupère l'objet
        $user = Auth::user(); 

        $dernierTrajet = null;
        $vehicules = collect();

        try {
            //On récupère le dernier trajet créé par l'utilisateur
            $dernierTrajet = $user->trajets()->orderBy('id', 'desc')->first(); 

            //Récupère tous les véhicules de l'utilisateur
            $vehicules = $user->vehicules()->get(); 
        }
        catch (\Exception $e) {

        }

        //Transmet les variables à la page "proposer-un-trajet"
        return view('trajets.create', compact('dernierTrajet', 'vehicules'));
    }

    //Enregistrement
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $vehicule = Vehicule::where('id', $request->id_vehicule)
                        ->where('id_utilisateur', Auth::id())
                        ->first();

        $limitePlaces = $vehicule ? (int)$vehicule->nombre_place : 0;

        $lieuDepartMin = strtolower($request->input('lieu_depart'));
        $lieuArriveeMin = strtolower($request->input('lieu_arrivee')); 


        $request->merge([
        'lieu_depart_min' => $lieuDepartMin,
        'lieu_arrivee_min' => $lieuArriveeMin,
        ]);

        $validatedData = $request->validate([
            'lieu_depart' => 'required|string|max:100',
            
            'lieu_arrivee' => [
                'required', 
                'string', 
                'max:100',
                function ($attribute, $value, $fail) use ($request) {
                    if (strtolower($value) === strtolower($request->lieu_depart)) {
                        $fail('Le lieu de départ et le lieu d\'arrivée ne peuvent pas être identiques.');
                    }
                },
            ],

            'date_depart' => 'required|date|after_or_equal:today',
            
            'heure_depart' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    // On récupère la date choisie
                    $dateDepart = $request->input('date_depart');
                    
                    if ($dateDepart) {
                        // On crée une date complète
                        $trajetDate = \Carbon\Carbon::parse($dateDepart . ' ' . $value);

                        // On crée la limite de 5 minutes à partir de maintenant
                        $limite = \Carbon\Carbon::now()->addMinutes(5)->startOfMinute();
                                    
                        // Si le trajet est passé
                        if ($trajetDate->lessThan($limite)) {
                            $fail('Le trajet doit débuter au moins 5 minutes après l\'heure actuelle.');
                        }
                    }
                },
            ],
            'places_disponibles' => "required|integer|min:1|max:{$limitePlaces}",
            'id_vehicule' => 'required|exists:vehicule,id', 
            'prix' => 'required|integer|min:0|max:100',
        ]);

        $heureArriveeCalcul = '00:00:00'; 
        if ($request->filled('duree_trajet')) {
            try {
                $dateComplete = Carbon::createFromFormat(
                    'Y-m-d H:i', 
                    $request->date_depart . ' ' . $request->heure_depart
                );
                
                $dateArrivee = $dateComplete->addSeconds((int)$request->duree_trajet);
                
                $dateArrivee->second(0); 

                $heureArriveeCalcul = $dateArrivee->format('H:i:s');
            } catch (\Exception $e) {}
        }

        //Enregistrement du Trajet
        $trajet = Trajet::create([
            'id_utilisateur' => Auth::id(),
            'id_vehicule' => $validatedData['id_vehicule'],
            'lieu_depart' => $validatedData['lieu_depart'],
            'lieu_arrivee' => $validatedData['lieu_arrivee'],
            'date_depart' => $validatedData['date_depart'],
            'heure_depart' => $validatedData['heure_depart'],
            'place_disponible' => $validatedData['places_disponibles'],
            'prix' => $validatedData['prix'],
            'heure_arrivee' => $heureArriveeCalcul, 
        ]);

        return redirect()->route('trajets.confirmation')->with('success', 'Votre trajet a été publié avec succès !');
    }

    //Confirmation de la création du trajet
    public function confirmation(){
        if(!Auth::check()){
            return redirect()->route('login');
        }
        $message = session('success');

        //Evite d'acceder à la page si on n'a pas créé de trajet 
        if(!$message){
            return redirect()->route('accueil');
        }

        return view('trajets.confirmation', compact('message'));
    }

    public function historique()
    {
        $userId = Auth::id();

        // ID des trajets réservés en tant que passager
        $idsReservations = DB::table('reserver')
            ->where('id_utilisateur', $userId)
            ->pluck('id_trajet')
            ->toArray();

        // Trajets à venir (conducteur ou passager)
        $trajetsAvenir = Trajet::with('conducteur')
            ->where(function ($query) use ($userId, $idsReservations) {
                $query->where('id_utilisateur', $userId)
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

        // Trajets passés (conducteur ou passager)
        $trajetsPasses = Trajet::with('conducteur')
            ->where(function ($query) use ($userId, $idsReservations) {
                $query->where('id_utilisateur', $userId)
                      ->orWhereIn('id', $idsReservations);
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

        return view('historique-trajet', compact('trajetsAvenir', 'trajetsPasses'));
    }

    public function destroy($id)
    {
        $trajet = \App\Models\Trajet::findOrFail($id);

        if ($trajet->id_utilisateur != auth()->id()) { 
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce trajet.');
        }

        $trajet->delete();

        return back()->with('success', 'Votre trajet a bien été annulé.');
    }

}