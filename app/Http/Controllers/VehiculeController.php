<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehiculeController extends Controller
{
    // Affiche le formulaire d'ajout
    public function create()
    {
        return view('vehicule.create');
    }

    // SAUVEGARDER UN NOUVEAU VÉHICULE
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'Marque' => 'required|string|max:20',
            'Modele' => 'required|string|max:20',
            'Couleur' => 'required|string|max:20',
            'Immatriculation' => 'required|string|max:10', // Adapter selon ta BDD
            'NombrePlace' => 'required|integer|min:1|max:9',
        ]);

        // 2. Création
        $vehicule = new Vehicule();
        $vehicule->Marque = $request->Marque;
        $vehicule->Modele = $request->Modele;
        $vehicule->Couleur = $request->Couleur;
        $vehicule->Immatriculation = $request->Immatriculation;
        $vehicule->NombrePlace = $request->NombrePlace;
        $vehicule->ID_Utilisateur = Auth::id(); // On lie à l'utilisateur connecté

        $vehicule->save();

        // 3. Redirection
        return redirect()->route('profile.show')->with('status', 'vehicle-added');
    }

    // SUPPRIMER UN VÉHICULE
    public function destroy($id)
    {
        // On cherche le véhicule, mais on vérifie bien qu'il appartient à l'utilisateur connecté !
        // (Sécurité pour ne pas supprimer la voiture du voisin)
        $vehicule = Vehicule::where('ID_Vehicule', $id)
                            ->where('ID_Utilisateur', Auth::id())
                            ->firstOrFail();

        $vehicule->delete();

        return back()->with('status', 'vehicle-deleted');
    }
}