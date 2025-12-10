<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trajet; 
use App\Models\Vehicule;
use App\Models\Utilisateur;

class TrajetController extends Controller
{
    /**
     * Affiche le formulaire de création de trajet. (Méthode appelée par la route GET)
     */
    public function create()
    {
        // 1. PROTECTION DÉSACTIVÉE TEMPORAIREMENT POUR LE DÉVELOPPEMENT
        /*
        if (!Auth::check()) {
            return redirect()->route('login'); 
        }
        */

        // Initialisation des variables nécessaires à la vue
        $dernierTrajet = null;
        $vehicules = collect(); 

        // Si nous étions connectés, on essaierait de récupérer les données BDD
        if (Auth::check()) {
             $user = Auth::user();
            try {
                // Ces lignes dépendent des Modèles BDD (Trajet, Vehicule) et des relations existantes
                $dernierTrajet = $user->trajets()->latest()->first(); 
                $vehicules = $user->vehicules()->get(); 
            } catch (\Exception $e) {
                // Si les Modèles ne sont pas prêts (ce qui est le cas), on ignore l'erreur
            }
        }
        
        // 2. Retourne la vue Blade et passe les variables.
        // La vue se trouve dans 'resources/views/trajets/create.blade.php'
        return view('trajets.create', compact('dernierTrajet', 'vehicules'));
    }

    // ... (la méthode store() reste inchangée pour le moment)
}