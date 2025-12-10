<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['vehicules', 'preference']);
        return view('profile.edit', ['user' => $user]);
    }

    public function update(Request $request): RedirectResponse
        {
            // Validation
            $validated = $request->validate([
                'firstname' => ['required', 'string', 'max:255'],
                'lastname'  => ['required', 'string', 'max:255'],
                'Accepte_animaux' => ['boolean'], // Noms des checkbox HTML
                'Accepte_fumeurs' => ['boolean'],
                'Accepte_musique' => ['boolean'],
            ]);

            $user = $request->user();

            // 1. Mise à jour User
            $user->prenom = $validated['firstname'];
            $user->nom    = $validated['lastname'];
            $user->save();

            // 2. Mise à jour Préférences (C'est ce bloc qu'il manquait !)
            // On utilise updateOrCreate pour créer la ligne si elle n'existe pas encore
            $user->preference()->updateOrCreate(
                ['id_utilisateur' => $user->id], // Condition de recherche
                [
                    // Colonne BDD (minuscule) => Valeur Formulaire (Majuscule)
                    'accepte_animaux'    => $request->Accepte_animaux,
                    'accepte_fumeurs'    => $request->Accepte_fumeurs,
                    'accepte_musique'    => $request->Accepte_musique,
                    'accepte_discussion' => 1, // Valeur par défaut
                ]
            );

            return redirect()->route('profile.show')->with('status', 'profile-updated');
        }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ],[
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.current_password' => 'Le mot de passe est incorrect.',
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
        $allowed = ['accepte_animaux', 'accepte_fumeurs', 'accepte_musique'];
        $field = $request->input('field');

        if (!in_array($field, $allowed)) {
            return back();
        }

        $user = $request->user();

        $pref = $user->preference()->firstOrCreate(
            ['id_utilisateur' => $user->id],
            ['accepte_discussion' => 1] // Valeur par défaut (1 ou 0 pour tinyint)
        );

        $pref->$field = ! $pref->$field;
        $pref->save();

        return back();
    }

    /**
     * Met à jour UNIQUEMENT la photo de profil (Action rapide)
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'Photo' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        // Suppression ancienne photo
        if ($user->Photo) {
            Storage::disk('public')->delete($user->Photo);
        }

        // Sauvegarde nouvelle photo
        $path = $request->file('Photo')->store('avatars', 'public');
        $user->Photo = $path;
        $user->save();

        return back()->with('status', 'avatar-updated'); // "back()" renvoie sur la même page
    }


    /**
     * Affiche le formulaire de modification du mot de passe.
     */
    public function editSecurity(Request $request): View
    {
        return view('profile.security', [
            'user' => $request->user(),
        ]);
    }
    /**
     * Met à jour le mot de passe de l'utilisateur.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Affiche le profil public d'un autre utilisateur.
     */
    public function showPublic($id): View
    {
        // On récupère l'utilisateur demandé avec ses infos
        // 'findOrFail' renvoie une erreur 404 si l'ID n'existe pas
        $user = \App\Models\User::with(['vehicules', 'preference'])->findOrFail($id);

        return view('profile.public', [
            'user' => $user,
        ]);
    }
}