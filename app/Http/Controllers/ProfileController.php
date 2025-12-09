<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(): View
    {
        // 1. L'Utilisateur
        $user = new \stdClass();
        $user->name = "Marie Dupont";
        $user->Nom = "Dupont";
        $user->Prenom = "Marie";
        $user->email = "marie.dupont@etu.u-picardie.fr";
        $user->Numero = "06 12 34 56 78";
        $user->created_at = now();

        // 2. Le Véhicule (Comme dans la table VEHICULE
        $vehicule = new \stdClass();
        $vehicule->Marque = "Peugeot";
        $vehicule->Modele = "208";
        $vehicule->Couleur = "Blanc";
        $vehicule->Immatriculation = "AB-123-CD";
        $vehicule->NombrePlace = 4;
        
        // On attache le véhicule à l'utilisateur
        $user->vehicule = $vehicule; 

        // 3. Les Préférences (Comme dans la table PREFERENCE
        $preference = new \stdClass();
        $preference->Accepte_animaux = true; // true = 1 dans la BDD
        $preference->Accepte_fumeurs = false;
        $preference->Accepte_musique = true;
        $preference->Accepte_discussion = 3; // Echelle de 1 à 5
        
        // On attache les préférences
        $user->preference = $preference;

        return view('profile.show', [
            'user' => $user,
        ]);
    }
}
