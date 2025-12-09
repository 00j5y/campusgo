<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    protected $table = 'PREFERENCE'; // Singulier
    protected $primaryKey = 'ID_Preference';
    public $timestamps = false;

    protected $fillable = [
        'Accepte_animaux',
        'Accepte_fumeurs',
        'Accepte_musique',
        'Accepte_discussion',
        'ID_Utilisateur'
    ];
}
