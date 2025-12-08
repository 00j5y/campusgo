<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateur';

    protected $primaryKey = 'ID_Utilisateur';

    public $timestamps = false;

    protected $fillable = [
        'Identifiant',
        'MotDePasse',
        'Nom',
        'Prenom',
        'Mail',
        'Numero',
        'EstAdministrateur',
    ];


    protected $hidden = [
        'MotDePasse',
    ];

    public function getAuthPassword()
    {
        return $this->MotDePasse;
    }
    
    public function getEmailForPasswordReset()
    {
        return $this->Mail;
    }
}