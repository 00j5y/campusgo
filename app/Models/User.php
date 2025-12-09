<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Vehicule;
use App\Models\Preference;

class User extends Authenticatable
{

    //protected $table = 'UTILISATEUR'; 
    
    //protected $primaryKey = 'ID_Utilisateur';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'utilisateur';

    public $timestamps = false;

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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'mdp',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->mdp;
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mdp' => 'hashed',
        ];
    }

    public function vehicules()
    {
        return $this->hasMany(Vehicule::class, 'ID_Utilisateur', 'ID_Utilisateur');
    }

    public function preference()
    {
        return $this->hasOne(Preference::class, 'ID_Utilisateur', 'ID_Utilisateur');
    }

}
