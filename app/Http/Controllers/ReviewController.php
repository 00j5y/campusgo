<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Trajet;
use App\Models\Avis;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Affiche la liste des avis reçus.
     */
    public function index(): View
    {
        $userId = Auth::id();

        $reviews = Avis::where('id_destinataire', $userId)
                       ->with('auteur')
                       ->orderBy('created_at', 'desc') // Les plus récents en premier
                       ->get();

        // calcul de la moyenne et du total
        $total = $reviews->count();
        $average = $total > 0 ? round($reviews->avg('note'), 1) : 0;

        return view('reviews.index', [
            'reviews' => $reviews,
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

        $trajet = Trajet::findOrFail($validated['trajet_id']);
        
        $idAuteur = Auth::id();
        
        $idDestinataire = $trajet->id_utilisateur;

        // pour ne pas se noter soi-même
        if ($idAuteur == $idDestinataire) {
            return back()->with('error', 'Vous ne pouvez pas vous noter vous-même.');
        }

        // créer l'avis
        $avis = new Avis();
        $avis->note = $validated['note'];
        $avis->commentaire = $validated['commentaire'];
        $avis->id_trajet = $validated['trajet_id'];
        $avis->id_auteur = $idAuteur;
        $avis->id_destinataire = $idDestinataire;
        $avis->save();

        return redirect()->route('historique-trajet')
                         ->with('success', 'Votre avis a bien été publié !');
    }
}