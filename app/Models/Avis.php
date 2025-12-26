<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    use HasFactory;

    protected $table = 'avis';

    protected $fillable = [
        'note',
        'commentaire',
        'id_trajet',
        'id_auteur',
        'id_destinataire',
    ];

    public function auteur()
    {
        return $this->belongsTo(User::class, 'id_auteur');
    }

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'id_destinataire');
    }

    public function trajet()
    {
        return $this->belongsTo(Trajet::class, 'id_trajet');
    }
}