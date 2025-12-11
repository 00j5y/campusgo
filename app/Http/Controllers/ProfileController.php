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
use App\Models\HistoriqueConnexion;
use Illuminate\Support\Str;

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
                'num_tel'   => ['nullable', 'string', 'regex:/^0[1-9]([ .-]?[0-9]{2}){4}$/'],
                'photo'     => ['nullable', 'image', 'max:2048'],
                'Accepte_animaux' => ['boolean'], // Noms des checkbox HTML
                'Accepte_fumeurs' => ['boolean'],
                'Accepte_musique' => ['boolean'],
                'accepte_discussion' => ['required', 'integer', 'min:1', 'max:5'],
            ],[
                'firstname.required' => "Votre prénom est obligatoire.",
                'firstname.max'      => "Ce prénom est un peu trop long.",
                
                'lastname.required' => "Votre nom est obligatoire.",
                'lastname.max'      => "Ce nom est un peu trop long.",
                
                'photo.image' => "Le fichier doit être une image (JPG, PNG, etc.).",
                'photo.max'   => "L'image est trop lourde. La taille maximale est de 2 Mo.",
                
                'num_tel.regex' => "Numéro invalide. Format attendu : 06 12 34 56 78.",
                
                'accepte_discussion.required' => "Veuillez sélectionner votre niveau de discussion.",
                'accepte_discussion.min'      => "Valeur de discussion incorrecte.",
                'accepte_discussion.max'      => "Valeur de discussion incorrecte.",
            ]);

            $user = $request->user();


            $user->prenom = Str::title($validated['firstname']); 
            $user->nom = Str::upper($validated['lastname']);

            if ($request->filled('num_tel')) {
                $user->num_tel = preg_replace('/[^0-9]/', '', $request->input('num_tel'));
            } else {
                $user->num_tel = null; // Si le champ est vide, on met NULL en BDD
            }

            if ($request->hasFile('photo')) {
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                    }
            
            // Enregistrer la nouvelle
            $path = $request->file('photo')->store('avatars', 'public');
            $user->photo = $path;
        }

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

    /**
     * Affiche l'historique (colonnes en français)
     */
    public function history(Request $request): View
    {
        $logins = \App\Models\HistoriqueConnexion::where('id_utilisateur', $request->user()->id)
                              ->orderBy('date_connexion', 'desc')
                              ->limit(20)
                              ->get();

        return view('profile.history', ['logins' => $logins]);
    }

    /**
     * Affiche le formulaire de confidentialité
     */
    public function privacy(Request $request): View
    {
        $user = $request->user()->load('preference');
        return view('profile.privacy', ['user' => $user]);
    }

    /**
     * Sauvegarde la confidentialité
     */
    public function updatePrivacy(Request $request)
    {
        $user = $request->user();
        
        // On s'assure que la ligne préférence existe (sinon erreur)
        $pref = $user->preference()->firstOrCreate(
            ['id_utilisateur' => $user->id],
            ['accepte_discussion' => 1] // Valeur par défaut obligatoire si création
        );

        // Checkbox cochée = true, sinon false
        $pref->telephone_public = $request->has('telephone_public');
        $pref->trajets_publics = $request->has('trajets_publics');
        
        $pref->save();

        return back()->with('status', 'privacy-updated');
    }

    public function updateDiscussion(Request $request)
    {
        // On valide que c'est bien un chiffre entre 1 et 5
        $request->validate([
            'accepte_discussion' => 'required|integer|min:1|max:5',
        ]);

        $user = $request->user();
        
        // On récupère la préférence
        $pref = $user->preference()->firstOrCreate(['id_utilisateur' => $user->id]);

        // On enregistre la valeur du curseur
        $pref->accepte_discussion = $request->input('accepte_discussion');
        $pref->save();

        return back()->with('status', 'preference-updated');
    }
}