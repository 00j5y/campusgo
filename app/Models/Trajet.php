<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Avis;
use Illuminate\Support\Facades\Cache;

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
        return $this->belongsTo(User::class, 'id_utilisateur', 'id');
    }

    /**
     * Vérifie si l'utilisateur connecté a déjà donné un avis sur ce trajet
     */
    public function aDejaUnAvis()
    {
        if (!Auth::check()) {
            return false;
        }

        $id_utilisateur = Auth::id();

        $cacheKey = "avis_donne_u{$id_utilisateur}_t{$this->id}";
        if (Cache::has($cacheKey)) {
            return true; // Il a déjà voté selon le cache
        }

        return Avis::where('id_trajet', $this->id)
                   ->where('id_auteur', Auth::id())
                   ->exists();
    }

    public function passagers()
    {
        return $this->belongsToManyMany(User::class, 'reserver', 'id_trajet', 'reserver', 'id_trajet', 'id_utilisateur')
                    ->withTimestamps()
    }


}
