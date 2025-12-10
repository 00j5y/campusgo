<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $table = 'vehicule'; // Nom de la table

    // Pas de timestamps (created_at) dans ta table d'après l'image
    public $timestamps = false; 

    protected $fillable = [
        'marque',
        'modele',
        'couleur',
        'nombre_place',
        'immatriculation',
        'id_utilisateur', // Clé étrangère
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}