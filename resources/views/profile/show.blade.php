@extends('layouts.app')

@section('title', 'Mon Profil - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-noir">Mon Profil</h1>
            <p class="text-gris1 mt-2">Gérez vos informations personnelles et vos préférences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col items-center text-center">
                    <div class="relative mb-4">
                        <div class="w-24 h-24 bg-vert-principale/10 text-vert-principale rounded-full flex items-center justify-center text-3xl font-bold">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <button class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-md border border-gray-100 hover:bg-gray-50 transition-colors" title="Modifier la photo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gris1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    
                    <h2 class="text-xl font-bold text-noir">{{ $user->name }}</h2>
                    <p class="text-sm text-gris1 mt-1">Membre depuis {{ $user->created_at->format('M Y') }}</p>
                    
                    <div class="mt-6 inline-flex items-center gap-2 bg-vert-principale/10 px-4 py-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-principale" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-vert-principale font-medium text-sm">12 trajets effectués</span> </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-noir">Liens Rapides</h3>
                    </div>
                    <nav class="flex flex-col">
                        <a href="#" class="px-6 py-4 text-gris1 hover:bg-vert-principale/5 hover:text-vert-principale transition-colors flex items-center justify-between group">
                            <span class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-vert-principale" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0121 18.382V7.618a1 1 0 01-1.447-.894L15 7m0 13V7m0 0L9.553 4.553A1 1 0 005 5.382v10.236a1 1 0 001.447.894L9 17" /></svg>
                                Voir mes trajets
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <a href="#" class="px-6 py-4 text-gris1 hover:bg-vert-principale/5 hover:text-vert-principale transition-colors flex items-center justify-between group">
                            <span class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-vert-principale" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                Mes avis et évaluations
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </nav>
                    
                    <div class="p-4 border-t border-gray-100 mt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex justify-center items-center gap-2 text-red-500 bg-red-50 hover:bg-red-100 font-medium py-2.5 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Se Déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-noir flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-beige-principale flex items-center justify-center text-noir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </span>
                            Informations Personnelles
                        </h2>
                        <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-vert-principale hover:text-vert-principal-h hover:underline">Modifier</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gris1 mb-1">Prénom</p>
                            <p class="font-medium text-noir">{{ explode(' ', $user->name)[0] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gris1 mb-1">Nom</p>
                            <p class="font-medium text-noir">{{ explode(' ', $user->name)[1] ?? '' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gris1 mb-1">Email</p>
                            <p class="font-medium text-noir">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gris1 mb-1">Téléphone</p>
                            <p class="font-medium text-noir italic text-gray-400">Non renseigné</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-bold text-noir flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-beige-principale flex items-center justify-center text-noir">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> </span>
                            Mon Véhicule
                        </h2>
                        <a href="#" class="text-sm font-semibold text-vert-principale hover:text-vert-principal-h hover:underline">Modifier</a>
                    </div>
                    <p class="text-sm text-gris1 mb-6">Informations sur votre véhicule pour les trajets en tant que conducteur</p>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gris1 uppercase tracking-wide">Marque et Modèle</p>
                            <p class="font-semibold text-noir mt-1">Peugeot 208</p>
                        </div>
                        <div>
                            <p class="text-xs text-gris1 uppercase tracking-wide">Immatriculation</p>
                            <p class="font-semibold text-noir mt-1">AB-123-CD</p>
                        </div>
                        <div>
                            <p class="text-xs text-gris1 uppercase tracking-wide">Couleur</p>
                            <p class="font-semibold text-noir mt-1">Blanc</p>
                        </div>
                        <div>
                            <p class="text-xs text-gris1 uppercase tracking-wide">Nombre de places</p>
                            <p class="font-semibold text-noir mt-1">4 places</p>
                        </div>
                    </div>
                    
                    <button class="mt-4 flex items-center gap-1 text-sm font-medium text-vert-principale hover:text-vert-principal-h transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Ajouter un autre véhicule
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-beige-principale flex items-center justify-center text-noir">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        </span>
                        Préférences de Covoiturage
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                            <div class="mt-1 text-vert-principale">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" /></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-noir">Accepter les animaux</h4>
                                <p class="text-sm text-gris1">Autoriser les passagers avec des animaux de compagnie</p>
                            </div>
                            <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="toggle" id="toggle1" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-vert-principale"/>
                                <label for="toggle1" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                            <div class="mt-1 text-vert-principale">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" /></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-noir">Fumeur</h4>
                                <p class="text-sm text-gris1">Autoriser de fumer dans le véhicule</p>
                            </div>
                            <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="toggle" id="toggle2" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300"/>
                                <label for="toggle2" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>

                         <div class="flex items-start gap-4 p-4 rounded-lg hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                            <div class="mt-1 text-vert-principale">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-noir">Musique</h4>
                                <p class="text-sm text-gris1">Écouter de la musique pendant le trajet</p>
                            </div>
                             <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="toggle" id="toggle3" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-vert-principale"/>
                                <label for="toggle3" class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-noir mb-4">Sécurité</h3>
                    <div class="space-y-3">
                        <a href="#" class="block text-gris1 hover:text-vert-principale transition-colors flex justify-between">
                            Changer le mot de passe
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <hr class="border-gray-100">
                        <a href="#" class="block text-gris1 hover:text-vert-principale transition-colors flex justify-between">
                            Historique de connexion
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <hr class="border-gray-100">
                        <a href="#" class="block text-gris1 hover:text-vert-principale transition-colors flex justify-between">
                            Paramètres de confidentialité
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* CSS rapide pour les toggles (interrupteurs) */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #68A35E; /* Vert principale (adaptez le code hex si besoin) */
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #68A35E; /* Vert principale */
    }
    .toggle-checkbox {
        right: 20px; /* Position initiale à gauche */
        transition: all 0.3s;
    }
    .toggle-label {
        width: 44px; /* Largeur du fond */
    }
</style>
@endsection