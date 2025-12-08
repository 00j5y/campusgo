@extends('layouts.auth')

@section('title', 'Connexion/Inscription - Campus\'GO')

@section('content')
<div class="min-h-screen bg-white lg:flex">

    <div class="hidden lg:flex lg:w-1/2 bg-cover relative" style="background-image: url('{{ asset('images/auth/iut.jpg') }}');">

        <div class="relative z-10 w-full p-12 flex flex-col justify-between text-white h-full">
            <a href="{{ url('/') }}" class="flex items-center">
                <div class="bg-white text-vert-principale p-2 rounded-full font-bold flex items-center shadow-lg">
                    <img class="size-12 mx-3" src="{{ asset('favicon.ico') }}">
                        <span class="text-vert-principale">Campus</span>
                        <span class="text-beige-second">'Go</span>
                </div>
            </a>

            <div class="mb-10 bg-black/30 rounded-2xl p-4">
                <h1 class="text-4xl font-bold mb-6">Bienvenue sur Campus'Go</h1>
                <p class="text-lg mb-8">
                    La plateforme de covoiturage dédiée à la communauté de l'IUT d'Amiens. 
                    Partagez vos trajets, faites des économies et contribuez à un campus plus écologique.
                </p>
                
                <ul class="font-medium">
                    <li class="flex items-center">
                        <img class="size-5 mr-2" src="{{ asset('images/accueil/icones/valider.png') }}">
                        Réservé aux membres de l'IUT d'Amiens
                    </li>
                    <li class="flex items-center">
                        <img class="size-5 mr-2" src="{{ asset('images/accueil/icones/valider.png') }}">
                        Profils vérifiés et sécurisés
                    </li>
                    <li class="flex items-center">
                        <img class="size-5 mr-2" src="{{ asset('images/accueil/icones/valider.png') }}">
                        Économisez jusqu'à 50% sur vos trajets
                    </li>
                </ul>
            </div>

            <div class="flex items-center space-x-12 font-bold border-t border-white/20 pt-8">
                <div><span class="block text-3xl">250+</span><span class="text-sm">Membres actifs</span></div>
                <div><span class="block text-3xl">500+</span><span class="text-sm">Trajets partagés</span></div>
                <div><span class="block text-3xl">2.5T</span><span class="text-sm">CO₂ économisé</span></div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md">
            
            <div class="bg-gray-100 p-1 rounded-lg flex mb-8">
                <button id="tab-login" class="w-1/2 text-center py-2 rounded-md text-sm font-medium transition-all bg-white shadow-sm">
                    Connexion
                </button>
                <button id="tab-register" class="w-1/2 text-center py-2 rounded-md text-sm font-medium transition-all text-gris1 hover:text-gris2">
                    Inscription
                </button>
            </div>

            <div id="login-view">
                @section('title', 'Connexion - Campus\'GO')
                <h2 class="text-2xl font-bold text-noir mb-2">Connectez-vous</h2>
                <p class="text-noir mb-8">Accédez à votre compte Campus'Go</p>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf <div>
                        <label class="block text-sm font-medium text-noir mb-1">Email IUT</label>
                        <input type="email" name="email" required placeholder="prenom.nom@etud.u-picardie.fr" class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:bg-white focus:ring-1 focus:ring-vert-principale outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-noir mb-1">Mot de passe</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:bg-white focus:ring-1 focus:ring-vert-principale outline-none transition text-sm">
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-noir">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-vert-principale focus:ring-vert-principale">
                            <span class="ml-2">Se souvenir de moi</span>
                        </label>
                        <a href="#" class="text-vert-principale hover:underline font-medium">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="w-full bg-vert-principale hover:bg-green-800 text-white font-bold py-3 px-4 rounded-md transition duration-300 flex items-center justify-center">
                        Se connecter
                        <img src="{{  asset('images/accueil/icones/fleche-droite.png') }}" class="size-6 ml-1">
                    </button>
                </form>
            </div>

            <div id="register-view" class="hidden">
                <h2 class="text-2xl font-bold text-noir mb-2">Créer un compte</h2>
                <p class="text-gray-500 mb-8">Rejoignez la communauté Campus'Go</p>

                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf <div class="flex gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-noir mb-1">Prénom</label>
                            <input type="text" name="firstname" required placeholder="Leo" class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-noir mb-1">Nom</label>
                            <input type="text" name="lastname" required placeholder="Baudry" class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-noir mb-1">Email IUT</label>
                        <input type="email" name="email" required placeholder="prenom.nom@etud.u-picardie.fr" class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                        <p class="text-xs text-gray-500 mt-1">Email universitaire obligatoire.</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-noir mb-1">Mot de passe</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-noir mb-1">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••" class="w-full px-4 py-3 rounded-md bg-gray-50 border border-gray-200 focus:border-vert-principale focus:ring-vert-principale outline-none text-sm">
                    </div>

                    <button type="submit" class="w-full bg-vert-principale hover:bg-green-800 text-white font-bold py-3 px-4 rounded-md transition duration-300 flex items-center justify-center">
                        Créer mon compte
                        <img src="{{  asset('images/accueil/icones/fleche-droite.png') }}" class="size-6 ml-1">
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/auth-menu.js')
@endpush