<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest; // <--- IMPORTANT : On utilise votre Request
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\User;

class ProfileController extends Controller
{
    // Affiche le formulaire
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['vehicules', 'preference']);
        return view('profile.edit', ['user' => $user]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->fill([
            'prenom' => Str::title($validated['firstname']),
            'nom'    => Str::upper($validated['lastname']),
            'email'  => $validated['email'],
        ]);


        if (!empty($validated['num_tel'])) {
            $user->num_tel = preg_replace('/[^0-9]/', '', $validated['num_tel']);
        } else {
            $user->num_tel = null;
        }
        
        if ($request->boolean('delete_photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
        }

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('avatars', 'public');
            $user->photo = $path;
        }

        $user->save();

        $user->preference()->updateOrCreate(
            ['id_utilisateur' => $user->id],
            [
                'accepte_animaux'    => $request->boolean('Accepte_animaux'),
                'accepte_fumeurs'    => $request->boolean('Accepte_fumeurs'),
                'accepte_musique'    => $request->boolean('Accepte_musique'),
                'accepte_discussion' => $validated['accepte_discussion'],
            ]
        );

        return redirect()->route('profile.show')->with('success', 'Votre profil a été modifié avec succès.');
    }

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
        return view('profile.show', [
            'user' => $request->user()->load(['vehicules', 'preference']),
        ]);
    }

    public function togglePreference(Request $request)
    {
        $allowed = ['accepte_animaux', 'accepte_fumeurs', 'accepte_musique'];
        $field = $request->input('field');

        if (!in_array($field, $allowed)) {
            return back();
        }

        $user = $request->user();
        $pref = $user->preference()->firstOrCreate(['id_utilisateur' => $user->id], ['accepte_discussion' => 3]);

        $pref->$field = ! $pref->$field;
        $pref->save();

        return back()->with('success', 'Préférence mise à jour.');
    }

    public function editSecurity(Request $request): View
    {
        return view('profile.security', [
            'user' => $request->user(),
        ]);
    }


    // Affiche le profil public
    public function showPublic($id)
    {
        $user = User::with(['vehicules', 'preference'])->findOrFail($id);
        $avisRecus = \App\Models\Avis::where('id_destinataire', $id)->get();
        $nombreAvis = $avisRecus->count();
        $moyenne = $nombreAvis > 0 ? round($avisRecus->avg('note'), 1) : 0;

        return view('profile.public', compact('user', 'nombreAvis', 'moyenne'));
    }

    // Historique
    public function history(Request $request): View
    {
        $logins = \App\Models\HistoriqueConnexion::where('id_utilisateur', $request->user()->id)
            ->orderBy('date_connexion', 'desc')
            ->limit(20)
            ->get();

        return view('profile.history', ['logins' => $logins]);
    }

    // Paramètres
    public function setup(Request $request): View
    {
        $user = $request->user()->load('preference');
        return view('profile.setup', ['user' => $user]);
    }

    public function updateSetup(Request $request)
    {
        $user = $request->user();
        $pref = $user->preference()->firstOrCreate(['id_utilisateur' => $user->id], ['accepte_discussion' => 3]);

        $pref->telephone_public = $request->has('telephone_public');
        $pref->max_detour = $request->input('max_detour');
        $pref->max_attente = $request->input('max_attente');
        $pref->save();

        return back()->with('success', 'Vos préférences ont été mises à jour avec succès.');
    }

    // Slider Discussion
    public function updateDiscussion(Request $request)
    {
        $request->validate(['accepte_discussion' => 'required|integer|min:1|max:5']);
        
        $user = $request->user();
        $pref = $user->preference()->firstOrCreate(['id_utilisateur' => $user->id]);
        
        $pref->accepte_discussion = $request->input('accepte_discussion');
        $pref->save();

        return back()->with('success', 'Niveau de discussion mis à jour.');
    }

    public function destroyPhoto()
    {
        $user = Auth::user();
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
            $user->save();
            return back()->with('success', 'Votre photo a été supprimée.');
        }
        return back()->with('error', 'Aucune photo à supprimer.');
    }
}