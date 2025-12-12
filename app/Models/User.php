<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Vehicule;
use App\Models\Trajet;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'utilisateur';

    protected $primaryKey = 'id'; 
    public $incrementing = true; //

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
        'numTel', 
        'estAdmin',
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

    public function getAuthPasswordName()
    {
        return 'mdp';
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
    public function reservations()
    {
        return $this->belongsToMany(Trajet::class, 'reserver', 'id_utilisateur', 'id_trajet');

    
    public function vehicules() {
        return $this->hasMany(Vehicule::class, 'id_utilisateur', 'id');
    }

    public function trajets() {
        return $this->hasMany(Trajet::class, 'id_utilisateur', 'id');
    }
}
