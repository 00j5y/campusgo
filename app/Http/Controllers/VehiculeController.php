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

   public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            'Marque' => 'required|string|max:20', // Ici c'est le nom du champ HTML (Majuscule)
            'Modele' => 'required|string|max:20',
            'Couleur' => 'required|string|max:20',
            'immatriculation' => 'required|string|regex:/^[A-Za-z]{2}[-\s]?[0-9]{3}[-\s]?[A-Za-z]{2}$/',
            'NombrePlace' => 'required|integer|min:1|max:9',
        ],
        [
            'immatriculation.regex' => "Le format de l'immatriculation est invalide. Exemple valide : AB-123-CD",
        ]);

        $cleanImmat = preg_replace('/[^A-Za-z0-9]/', '', $request->input('immatriculation'));

        // 2. Création
        $vehicule = new Vehicule();
        
        // À GAUCHE : Nom de la colonne BDD (minuscule)
        // À DROITE : Nom du champ Formulaire (Majuscule)
        $vehicule->marque = $request->Marque;
        $vehicule->modele = $request->Modele;
        $vehicule->couleur = $request->Couleur;
        $vehicule->immatriculation = strtoupper($cleanImmat);

        $vehicule->nombre_place = $request->NombrePlace; // Attention au _
        
        $vehicule->id_utilisateur = Auth::id(); // Clé étrangère en minuscule

        $vehicule->save();

        // 3. Redirection
        return redirect()->route('profile.show')->with('status', 'vehicle-added');
    }

    public function destroy($id)
        {
            // On cherche le véhicule par son 'id' (nouvelle BDD)
            // Et on vérifie toujours qu'il appartient à l'utilisateur (id_utilisateur)
            $vehicule = Vehicule::where('id', $id) 
                                ->where('id_utilisateur', Auth::id())
                                ->firstOrFail();

            $vehicule->delete();

            return back()->with('status', 'vehicle-deleted');
        }
}