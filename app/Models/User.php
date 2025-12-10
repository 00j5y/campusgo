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

    // 1. Nom de la table dans la BDD
    protected $table = 'utilisateur';

    public $timestamps = false;

    // 2. Les colonnes modifiables (Français)
    protected $fillable = [
        'prenom',
        'nom',
        'email',
        'mdp',
        'estAdmin',
        'photo',
        'num_tel',
    ];

    // 3. Cacher le mot de passe et le token pour la sécurité
    protected $hidden = [
        'mdp',
        'remember_token',
    ];

    // 4. IMPORTANT : Dire à Laravel que le mot de passe s'appelle 'mdp'
    public function getAuthPassword()
    {
        return $this->mdp;
    }

    // --- RELATIONS ---

    // Un utilisateur a plusieurs véhicules
    public function vehicules()
    {
        // On précise la clé étrangère 'id_utilisateur'
        return $this->hasMany(Vehicule::class, 'id_utilisateur');
    }

    // Un utilisateur a une préférence
    public function preference()
    {
        return $this->hasOne(Preference::class, 'id_utilisateur');
    }
}