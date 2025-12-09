<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Affiche la liste des avis reçus.
     */
    public function index(): View
    {
        // Simulation de données (En attendant la BDD finale)
        $reviews = [
            [
                'author' => 'Thomas',
                'rating' => 5,
                'comment' => 'Trajet super agréable, conducteur ponctuel et très sympa ! Je recommande.',
                'date' => 'Il y a 2 jours',
            ],
            [
                'author' => 'Sarah',
                'rating' => 4,
                'comment' => 'Bonne conduite, un peu de retard au départ mais trajet confortable.',
                'date' => 'Il y a 1 semaine',
            ],
            [
                'author' => 'Lucas',
                'rating' => 5,
                'comment' => 'Parfait !',
                'date' => 'Il y a 1 mois',
            ],
        ];

        return view('reviews.index', [
            'reviews' => $reviews,
            'average' => 4.7, // Note moyenne simulée
            'total' => 3      // Nombre total
        ]);
    }

    /**
     * Affiche le formulaire pour laisser un avis (Simulation).
     */
    public function create()
    {
        // On simule les infos du trajet (comme sur ton PDF)
        $trip = [
            'driver_name' => 'Sophie Bernard',
            'from' => 'Centre-ville Amiens',
            'to' => 'IUT Amiens, Avenue des Facultés',
            'time' => '14:00',
            'date' => '25 Octobre 2025',
        ];

        return view('reviews.create', ['trip' => $trip]);
    }
}