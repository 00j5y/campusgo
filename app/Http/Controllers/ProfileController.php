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
        $user = $request->user()->load(['vehicules', 'preference']);

        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
        {
            // 1. Validation des données reçues du formulaire
            $validated = $request->validate([
                'firstname' => ['required', 'string', 'max:255'],
                'lastname'  => ['required', 'string', 'max:255'],
                'phone'     => ['nullable', 'string', 'max:20'], // Peut être vide
                // Les checkboxes envoient "0" ou "1", on valide comme booléen
                'Accepte_animaux' => ['boolean'], 
                'Accepte_fumeurs' => ['boolean'],
                'Accepte_musique' => ['boolean'],
            ]);

            $user = $request->user();

            // 2. Mise à jour de la table UTILISATEUR (User)
            $user->firstname = $validated['firstname'];
            $user->lastname  = $validated['lastname'];
            
            // ATTENTION : Vérifie le nom de ta colonne téléphone en BDD !
            $user->phone = $validated['phone'];

            $user->save(); // On sauvegarde l'utilisateur

            // 3. Mise à jour (ou Création) de la table PREFERENCE
            $user->preference()->updateOrCreate(
                ['ID_Utilisateur' => $user->id], // Condition pour trouver la ligne
                [
                    'Accepte_animaux'    => $request->Accepte_animaux,
                    'Accepte_fumeurs'    => $request->Accepte_fumeurs,
                    'Accepte_musique'    => $request->Accepte_musique,
                    'Accepte_discussion' => 3, // Valeur par défaut si non modifiée
                ]
            );

            // 4. Retour au profil avec un petit message de succès (invisible pour l'instant)
            return redirect()->route('profile.show')->with('status', 'profile-updated');
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

    public function show(Request $request): View
    {
        $user = $request->user()->load(['vehicules', 'preference']);

        return view('profile.show', [
            'user' => $user,
    ]);
    }

    /**
     * Active ou désactive une préférence instantanément.
     */
    public function togglePreference(Request $request)
    {
        // 1. Sécurité : On n'autorise que ces 3 champs
        $allowed = ['Accepte_animaux', 'Accepte_fumeurs', 'Accepte_musique'];
        $field = $request->input('field');

        if (!in_array($field, $allowed)) {
            return back(); // Si on essaie de modifier autre chose, on annule
        }

        $user = $request->user();

        $pref = $user->preference()->firstOrCreate(
            ['ID_Utilisateur' => $user->id],
            ['Accepte_discussion' => 3] // Valeur par défaut obligatoires
        );

        $pref->$field = ! $pref->$field;
        $pref->save();

        // 4. On recharge la page pour voir le changement
        return back(); // back() renvoie à la page précédente
    }
}