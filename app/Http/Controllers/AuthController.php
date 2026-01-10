<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // Affichage de la page d'authentification
    public function index()
    {
        return view('auth.connexion');
    }

    // Tentative de connexion
    public function connexion(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $prenom = Auth::user()->prenom;

            return redirect()->intended(route('accueil'))
                ->with('success', "Ravi de vous revoir, $prenom !");
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
        {
            // 1. Validation
            $request->validate([
                'firstname' => ['required', 'string', 'max:255'],
                'lastname'  => ['required', 'string', 'max:255'],
                'email'     => ['required', 'string', 'email', 'max:255', 'unique:utilisateur'],
                'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            // 2. Création
            $user = User::create([
                'prenom' => $request->firstname,
                'nom'    => $request->lastname,
                'email'  => $request->email,
                'mdp'    => Hash::make($request->password),
            ]);

            Auth::login($user);

            return redirect()->route('profile.show')
                ->with('success', 'Bienvenue sur CampusGo ! Votre compte a été créé.');
        }

    // Affichage de la page d'inscription
    public function create()
    {
        return view('auth.inscription');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Vous avez été déconnecté. À bientôt !');
    }
}