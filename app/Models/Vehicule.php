<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $table = 'vehicule';

    public $timestamps = false; 

    protected $fillable = [
        'marque',
        'modele',
        'couleur',
        'nombre_place',
        'immatriculation',
        'id_utilisateur',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}