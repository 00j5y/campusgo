<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Vehicule;
use App\Models\Preference;
use App\Models\Trajet;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateur';

    protected $primaryKey = 'id'; 
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'mdp',
        'photo',
        'num_tel',
        'est_admin',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'mdp',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->mdp;
    }


    // Un utilisateur a plusieurs véhicules
    public function vehicules()
    {
        return $this->hasMany(Vehicule::class, 'id_utilisateur');
    }

    // Un utilisateur a une préférence
    public function preference()
    {
        return $this->hasOne(Preference::class, 'id_utilisateur');
    }
  
    //en tant que passager
    public function reservations()
    {
        return $this->belongsToMany(Trajet::class, 'reserver', 'id_utilisateur', 'id_trajet');
    }

    //en tant que conducteur
    public function trajets() {
        return $this->hasMany(Trajet::class, 'id_utilisateur', 'id');
    }

    //pour le nb de trajet en dessous la phot de profil dans la page profil
    public function getTrajetsEffectuesAttribute()
    {
        // conducteur dont la date est passée
        $nbConducteur = $this->trajets()
                             ->where('date_depart', '<', now())
                             ->count();

        // passager dont la date est passée
        $nbPassager = $this->reservations()
                           ->where('date_depart', '<', now())
                           ->count();

        return $nbConducteur + $nbPassager;
    }
}
