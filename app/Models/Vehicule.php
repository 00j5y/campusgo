<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    // On force le nom de la table (Singulier)
    protected $table = 'VEHICULE';
    
    // On précise la clé primaire
    protected $primaryKey = 'ID_Vehicule';

    // On désactive les timestamps si la table n'a pas les colonnes created_at/updated_at
    public $timestamps = false; 

    // Les colonnes modifiables
    protected $fillable = [
        'Marque',
        'Modele',
        'Couleur',
        'NombrePlace',
        'Immatriculation',
        'ID_Utilisateur' // ou 'user_id' selon votre BDD
    ];
}
