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

    /**
     * Affiche le formulaire pour laisser un avis
     */
    public function create($id_trajet)
    {
        // recup du trajet
        $trajet = Trajet::findOrFail($id_trajet);

        // verif que l'utilisateur ne se note pas lui-même
        if ($trajet->id_conducteur == auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous noter vous-même.');
        }

        return view('reviews.create', compact('trajet'));
    }

    /**
     * Enregistre un nouvel avis en base de données
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trajet_id'   => 'required|exists:trajet,id', // Vérifie que le trajet existe
            'note'        => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $trajetId = $validated['trajet_id'];

        $trajet = Trajet::findOrFail($trajetId);

        // pour éviter le spam, on utilise une clé en cache
        $cacheKey = "avis_donne_u{$userId}_t{$trajetId}";

        // On vérifie si cette clé existe déjà dans la mémoire du serveur
        if (Cache::has($cacheKey)) {
            return back()->with('error', 'Vous avez déjà donné votre avis sur ce trajet.');
        }

        if ($trajet->aDejaUnAvis()) {
            return back()->with('error', 'Vous avez déjà donné votre avis sur ce trajet.');
        }
        
        $idFantome = 999; 

        $idAuteur = $request->has('anonymous') ? $idFantome : Auth::id();

        $idDestinataire = $trajet->id_utilisateur;

        // Pour ne pas se noter soir même
        if (Auth::id() == $idDestinataire) {
            return back()->with('error', 'Vous ne pouvez pas vous noter vous-même.');
        }

        $avis = new Avis();
        $avis->note = $validated['note'];
        $avis->commentaire = $validated['commentaire'];
        $avis->id_trajet = $validated['trajet_id'];
        
        // Enregistrement du bon id
        $avis->id_auteur = $idAuteur; 
        
        $avis->id_destinataire = $idDestinataire;
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