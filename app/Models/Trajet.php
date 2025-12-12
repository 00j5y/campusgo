<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trajet extends Model
{
    protected $table = 'trajet';
    protected $primaryKey = 'id';

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
        'date_depart' => 'date',
        'heure_depart' => 'datetime:H:i',
        'heure_arrivee' => 'datetime:H:i',
        'place_disponible' => 'integer',
        'prix' => 'float',
    ];

    public function conducteur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur', 'id_utilisateur');
    }
}