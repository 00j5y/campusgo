<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\StoreVehiculeRequest;

class VehiculeController extends Controller
{
    // Affiche le formulaire d'ajout
    public function create()
    {
        return view('vehicule.create');
    }

   public function store(StoreVehiculeRequest $request)
    {


        $cleanImmat = preg_replace('/[^A-Za-z0-9]/', '', $request->input('immatriculation'));

        $vehicule = new Vehicule();
        
        $vehicule->marque = Str::title($request->Marque);
        $vehicule->modele = Str::title($request->Modele);
        $vehicule->couleur = Str::title($request->Couleur);
        $vehicule->immatriculation = strtoupper($cleanImmat);

        $vehicule->nombre_place = $request->NombrePlace;
        
        $vehicule->id_utilisateur = Auth::id();

        $vehicule->save();

        $redirect = ($request->input('source') === 'trajet') 
            ? redirect()->route('trajets.create')->with('new_vehicule_id', $vehicule->id)
            : redirect()->route('profile.show');

        return $redirect->with('success', 'Votre véhicule a été ajouté au garage !');
    }

    public function destroy($id)
        {
            // On cherche le véhicule par son 'id'
            // Et on vérifie toujours qu'il appartient à l'utilisateur (id_utilisateur)
            $vehicule = Vehicule::where('id', $id) 
                                ->where('id_utilisateur', Auth::id())
                                ->firstOrFail();

            $vehicule->delete();

            return back()->with('success', 'Le véhicule a été supprimé avec succès.');
        }
}