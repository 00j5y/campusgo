<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $table = 'preference'; // Nom de la table
    
    public $timestamps = false;

    protected $fillable = [
        'accepte_animaux',
        'accepte_fumeurs',
        'accepte_musique',
        'accepte_discussion',
        'id_utilisateur',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}