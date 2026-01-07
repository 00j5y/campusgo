<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Trajet;
use App\Models\Avis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    /**
     * Affiche la liste des avis reçus.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $avisRecus = Avis::where('id_destinataire', $userId)
                     ->with('auteur', 'trajet')
                     ->orderBy('created_at', 'desc')
                     ->get();

        // les avis émis anonymement ne peuvent donc pas êtres vus       
        $avisEmis = Avis::where('id_auteur', $userId)
                    ->with('destinataire', 'trajet')
                    ->orderBy('created_at', 'desc')
                    ->get();

        // calcul de la moyenne et du total
        $total = $avisRecus->count();
        $average = $total > 0 ? round($avisRecus->avg('note'), 1) : 0;

        return view('reviews.index', [
            'avisRecus' => $avisRecus,
            'avisEmis' => $avisEmis,
            'average' => $average,
            'total'   => $total
        ]);
    }

    public function create($id_trajet, $id_candidat = null)
    {
        $trajet = Trajet::findOrFail($id_trajet);
        $user = Auth::user();

        // 1. Déterminer qui est la cible (le destinataire de la note)
        if ($id_candidat) {
            // Si un ID est passé dans l'URL, c'est lui qu'on note
            $cible = \App\Models\User::findOrFail($id_candidat);
        } else {
            // Sinon par défaut, on note le conducteur
            $cible = $trajet->conducteur;
        }

        // 2. Vérifications de sécurité
        if ($cible->id == $user->id) {
            return back()->with('error', 'Vous ne pouvez pas vous noter vous-même.');
        }

        return view('reviews.create', compact('trajet', 'cible'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trajet_id'       => 'required|exists:trajet,id',
            'destinataire_id' => 'required|exists:utilisateur,id',
            'note'            => 'required|integer|min:1|max:5',
            'commentaire'     => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $trajetId = $validated['trajet_id'];
        $destinataireId = $validated['destinataire_id'];
        
        if ($userId == $destinataireId) {
            return back()->with('error', 'Vous ne pouvez pas vous noter vous-même.');
        }

        // Clé de cache unique pour ce couple Auteur -> Destinataire sur ce Trajet
        $cacheKey = "avis_u{$userId}_to_u{$destinataireId}_t{$trajetId}";

        if (Cache::has($cacheKey)) {
            return back()->with('error', 'Vous avez déjà donné votre avis à cette personne pour ce trajet.');
        }

        // Création de l'avis
        $avis = new Avis();
        $avis->note = $validated['note'];
        $avis->commentaire = $validated['commentaire'];
        $avis->id_trajet = $trajetId;
        $avis->id_auteur = $request->has('anonymous') ? 999 : $userId;
        $avis->id_destinataire = $destinataireId; // On utilise l'ID envoyé par le formulaire
        $avis->save();

        Cache::put($cacheKey, true, now()->addYear());

        return redirect()->route('historique-trajet')->with('success', 'Avis publié !');
    }

    public function destroy($id)
    {
    $avis = Avis::findOrFail($id);

    // Seul l'auteur peut supprimer son avis
    if ($avis->id_auteur !== Auth::id()) {
        return back()->with('error', 'Action non autorisée.');
    }

    $avis->delete();

    return back()->with('success', 'Votre avis a été supprimé.');
    }
}