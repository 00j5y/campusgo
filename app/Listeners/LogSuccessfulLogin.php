<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use App\Models\HistoriqueConnexion;

class LogSuccessfulLogin
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        HistoriqueConnexion::create([
            'id_utilisateur'    => $event->user->id,
            'adresse_ip'        => $this->request->ip(),
            'agent_utilisateur' => $this->request->userAgent(),
            'date_connexion'    => now(),
        ]);
    }
}