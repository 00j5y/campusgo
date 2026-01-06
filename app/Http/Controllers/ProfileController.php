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
use App\Models\Avis;
use App\Models\User;

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
                'delete_photo' => ['nullable', 'boolean'],
            ]);

            $user = $request->user();

                // 2. Mise à jour des infos textuelles
                $user->prenom = Str::title($validated['firstname']);
                $user->nom = Str::upper($validated['lastname']);

                if ($request->filled('num_tel')) {
                    $user->num_tel = preg_replace('/[^0-9]/', '', $request->input('num_tel'));
                } else {
                    $user->num_tel = null;
                }
                
                // on regarde si l'utilisateur a demandé la suppression
                if ($request->input('delete_photo') == '1') {
                    if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                        Storage::disk('public')->delete($user->photo);
                    }
                    $user->photo = null;
                }

                // on regarde si une NOUVELLE photo est envoyée
                if ($request->hasFile('photo')) {
                    // Si une photo existait encore (et n'a pas été supprimée juste avant), on l'efface
                    if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                        Storage::disk('public')->delete($user->photo);
                    }
                    
                    // enregistrement de la nouvelle photo
                    $path = $request->file('photo')->store('avatars', 'public');
                    $user->photo = $path;
                }

                $user->save();

            // On utilise updateOrCreate pour créer la ligne si elle n'existe pas encore
            $user->preference()->updateOrCreate(
                ['id_utilisateur' => $user->id],
                [
                    // Colonne BDD (minuscule) => Valeur Formulaire (Majuscule)
                    'accepte_animaux'    => $request->Accepte_animaux,
                    'accepte_fumeurs'    => $request->Accepte_fumeurs,
                    'accepte_musique'    => $request->Accepte_musique,
                    'accepte_discussion' => $validated['accepte_discussion'],
                ]
            );

            return redirect()->route('profile.show')->with('status', 'profile-updated');
        }

    /**
     * Supprime le compte utilisateur
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
        $allowed = ['accepte_animaux', 'accepte_fumeurs', 'accepte_musique'];
        $field = $request->input('field');

        if (!in_array($field, $allowed)) {
            return back();
        }

        $user = $request->user();

        $pref = $user->preference()->firstOrCreate(
            ['id_utilisateur' => $user->id],
            ['accepte_discussion' => 3] 
        );

        $pref->$field = ! $pref->$field;
        $pref->save();

        return back();
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
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'mdp' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Affiche le profil public d'un autre utilisateur.
     */
    public function showPublic($id)
    {
        $user = User::with(['vehicules', 'preference'])->findOrFail($id);

        $avisRecus = \App\Models\Avis::where('id_destinataire', $id)->get();

        $nombreAvis = $avisRecus->count();
        $moyenne = $nombreAvis > 0 ? round($avisRecus->avg('note'), 1) : 0;

        return view('profile.public', compact('user', 'nombreAvis', 'moyenne'));
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
     * Affiche le formulaire de paramètres
     */
    public function setup(Request $request): View
    {
        $user = $request->user()->load('preference');
        return view('profile.setup', ['user' => $user]);
    }

    /**
     * Sauvegarde la confidentialité
     */
    public function updateSetup(Request $request)
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

        return back()->with('status', 'setup-updated');
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

    public function destroyPhoto()
    {
        $user = Auth::user();

        if ($user->photo) {

            if (Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            
            $user->photo = null;
            $user->save();
            
            return back()->with('success', 'Votre photo de profil a été supprimée.');
        }

        return back()->with('error', 'Aucune photo à supprimer.');
    }
}
