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
                        $fail('Le lieu de départ et le lieu d\'arrivée doivent être différents.');
                    }
                },
            ],

            'date_depart' => 'required|date|after_or_equal:today',
            'heure_depart' => 'required',
            'places_disponibles' => 'required|integer|min:1|max:7',
            'id_vehicule' => 'required|exists:vehicule,id', 
        ], [
            'lieu_depart.required' => 'Le lieu de départ est obligatoire.',
            'lieu_depart.max' => 'Le lieu de départ ne doit pas dépasser 100 caractères.',
            
            'lieu_arrivee.required' => 'Le lieu d\'arrivée est obligatoire.',
            
            'date_depart.required' => 'La date de départ est requise.',
            'date_depart.after_or_equal' => 'Vous ne pouvez pas proposer un trajet dans le passé.',
            
            'heure_depart.required' => 'L\'heure de départ est requise.',
            
            'places_disponibles.required' => 'Merci d\'indiquer le nombre de places.',
            'places_disponibles.min' => 'Il faut au moins 1 place disponible.',
            'places_disponibles.max' => 'Maximum 7 places autorisées.',
            
            'id_vehicule.required' => 'Vous devez sélectionner un véhicule.',
            'id_vehicule.exists' => 'Le véhicule sélectionné est invalide.',
        ]);

        //Enregistrement du Trajet
        $trajet = Trajet::create([
            'id_utilisateur' => Auth::id(),
            'id_vehicule' => $validatedData['id_vehicule'],
            'lieu_depart' => $validatedData['lieu_depart'],
            'lieu_arrivee' => $validatedData['lieu_arrivee'],
            'date_depart' => $validatedData['date_depart'],
            'heure_depart' => $validatedData['heure_depart'],
            'place_disponible' => $validatedData['places_disponibles'],
            'prix' => 0,
            'heure_arrivee' => '00:00:00', 
        ]);
        
        //Redirection après publication
        $request->session()->flash('success_message', 'Votre trajet a été publié avec succès!');

        return redirect()->route('trajets.confirmation');
    }

    //Confirmation de la création du trajet
    public function confirmation(){
        if(!Auth::check()){
            return redirect()->route('login');
        }
        $message = session('success_message');

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

}