<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Trajet; 
use App\Models\Vehicule;
use App\Models\User;

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

        //Validation des données
        $validatedData = $request->validate([
            // Correspond au nouveau schéma BDD Trajet
            'lieu_depart' => 'required|string|max:100',
            'lieu_arrivee' => 'required|string|max:100|different:lieu_depart',
            'date_depart' => 'required|date|after_or_equal:today',
            'heure_depart' => 'required|date_format:H:i',
            'places_disponibles' => 'required|integer|min:1|max:7',
            'id_vehicule' => 'required|exists:vehicule,id', // S'assure que l'ID existe dans la table 'vehicule'
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

}