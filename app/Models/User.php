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

    const CREATED_AT = 'date_creation';

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
  
    public function reservations()
    {
        return $this->belongsToMany(Trajet::class, 'reserver', 'id_utilisateur', 'id_trajet');
    }

    public function trajets() {
        return $this->hasMany(Trajet::class, 'id_utilisateur', 'id');
    }
}
