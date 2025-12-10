<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    protected $table = 'trajet';

    protected $primaryKey = 'ID_Trajet';

    public $timestamps = false;

    protected $fillable = [
        'lieu_depart',        
        'lieu_arrivee',       
        'date_depart',        
        'heure_depart',
        'heure_arrivee',
        'place_disponible',  
        'prix',
        'id_vehicule',        
        'id_utilisateur',
    ];

    protected $casts = [
        'Date_' => 'date',
        'Heure_Depart' => 'datetime:H:i',
        'Heure_Arrivee' => 'datetime:H:i',
        'Place_Disponible' => 'integer',
        'Prix' => 'integer',
    ];

}