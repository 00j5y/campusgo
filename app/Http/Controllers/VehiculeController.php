<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            'Marque' => 'required|string|max:20',
            'Modele' => 'required|string|max:20',
            'Couleur' => 'required|string|max:20',
            'immatriculation' => 'required|string|regex:/^[A-Za-z]{2}[-\s]?[0-9]{3}[-\s]?[A-Za-z]{2}$/',
            'NombrePlace' => 'required|integer|min:1|max:9',
        ],
        [
            'Marque.required' => "Veuillez renseigner la marque du véhicule.",
            'Marque.max'      => "La marque est trop longue (20 caractères max).",
            
            'Modele.required' => "Veuillez renseigner le modèle du véhicule.",
            'Modele.max'      => "Le modèle est trop long (20 caractères max).",
            
            'Couleur.required' => "Veuillez indiquer la couleur.",
            'Couleur.max'      => "Le nom de la couleur est trop long.",
            
            'NombrePlace.required' => "Le nombre de places est obligatoire.",
            'NombrePlace.min'      => "Il faut au moins 1 place passager disponible.",
            'NombrePlace.max'      => "C'est un bus ? Maximum 9 places autorisées.",
            
            'immatriculation.required' => "L'immatriculation est obligatoire.",
            'immatriculation.regex'    => "Format invalide. Exemple attendu : AA-123-AA.",
        ]);

        $cleanImmat = preg_replace('/[^A-Za-z0-9]/', '', $request->input('immatriculation'));

        // 2. Création
        $vehicule = new Vehicule();
        
        $vehicule->marque = Str::title($request->Marque);
        $vehicule->modele = Str::title($request->Modele);
        $vehicule->couleur = Str::title($request->Couleur);
        $vehicule->immatriculation = strtoupper($cleanImmat);

        $vehicule->nombre_place = $request->NombrePlace;
        
        $vehicule->id_utilisateur = Auth::id();

        $vehicule->save();

        if ($request->input('source') === 'trajet') {
                return redirect()->route('trajets.create')
                                ->with('success', 'Véhicule ajouté ! Vous pouvez continuer votre trajet.')
                                ->with('new_vehicule_id', $vehicule->id);
            }

        return redirect()->route('profile.show')
                        ->with('success', 'Véhicule ajouté avec succès.');
    }

    public function destroy($id)
        {
            // On cherche le véhicule par son 'id'
            // Et on vérifie toujours qu'il appartient à l'utilisateur (id_utilisateur)
            $vehicule = Vehicule::where('id', $id) 
                                ->where('id_utilisateur', Auth::id())
                                ->firstOrFail();

            $vehicule->delete();

            return back()->with('status', 'vehicle-deleted');
        }
}