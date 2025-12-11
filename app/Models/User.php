<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Vehicule;
use App\Models\Preference;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateur';


    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'mdp',
        'est_admin',
        'photo',
        'num_tel',
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
}