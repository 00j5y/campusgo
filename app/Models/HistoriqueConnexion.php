<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueConnexion extends Model
{
    // 1. Nom de la table en français
    protected $table = 'historique_connexions';

    // car on utilise notre propre colonne 'date_connexion'
    public $timestamps = false;

    // 3. Champs autorisés
    protected $fillable = [
        'id_utilisateur', 
        'adresse_ip', 
        'agent_utilisateur', 
        'date_connexion'
    ];
    
    // Pour que Laravel gère 'date_connexion' comme une date
    protected $casts = [
        'date_connexion' => 'datetime',
    ];
}