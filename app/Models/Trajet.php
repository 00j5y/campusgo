<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    protected $table = 'trajet';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'Lieu_Depart',
        'Lieu_Arrivee',
        'Date_',
        'Heure_Depart',
        'Heure_Arrivee',
        'Place_Disponible',
        'Prix',
        'ID_Vehicule',
        'ID_Utilisateur',
    ];

    protected $casts = [
        'Date_' => 'date',
        'Heure_Depart' => 'datetime:H:i',
        'Heure_Arrivee' => 'datetime:H:i',
        'Place_Disponible' => 'integer',
        'Prix' => 'integer',
    ];
}