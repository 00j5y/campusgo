@extends('layouts.app')

@section('title', 'Mon Profil - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

        <x-flash-message />
        
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-noir">Mon Profil</h1>
            <p class="text-gris1 mt-2">Gérez vos informations personnelles et vos préférences</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 auto-rows-min">
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col items-center text-center">
                    
                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-beige-principale flex items-center justify-center mb-4">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/accueil/icones/personne-convivialite-vert.png') }}" alt="Avatar par défaut" class="w-10 h-10 object-contain">
                        @endif
                    </div>
                    
                    <h2 class="text-xl font-bold text-noir">{{ $user->prenom }} {{ $user->nom }}</h2>
                    <p class="text-sm text-gris1 mt-1">
                        Membre depuis {{ $user->created_at ? $user->created_at->format('M Y') : 'toujours' }}
                    </p>
                    
                    <div class="mt-6 inline-flex items-center gap-2 bg-vert-principale/10 px-4 py-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-principale" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="text-vert-principale font-medium text-sm">12 trajets effectués</span> 
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 lg:col-start-2 lg:row-span-3 space-y-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-bold text-xl text-noir flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-beige-principale flex items-center justify-center text-noir">
                                <img src="{{ asset('images/accueil/icones/user.png') }}" class="size-4">
                            </span>
                            Informations Personnelles
                        </h2>
                        <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-vert-principale hover:text-vert-principal-h hover:underline">Modifier</a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><p class="text-sm text-gris1 mb-1">Prénom</p><p class="text-lg text-gray-900">{{ $user->prenom }}</p></div>
                        <div><p class="text-sm text-gris1 mb-1">Nom</p><p class="text-lg text-gray-900">{{ $user->nom }}</p></div>
                        <div class="md:col-span-2"><p class="text-sm text-gris1 mb-1">Email</p><p class="text-lg text-gray-900">{{ $user->email }}</p></div>
                        <div class="pb-2">
                            <p class="text-sm text-gris1 mb-1">Téléphone</p>
                            <p class="text-lg {{ $user->num_tel ? 'text-gray-900' : 'italic text-gray-400 font-normal' }}">
                                {{ $user->num_tel ?? 'Non renseigné' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-noir flex items-center gap-2">
                            <span class="w-10 h-10 rounded-full bg-beige-principale flex items-center justify-center">
                                <img src="{{ asset('images/accueil/icones/voiture.png') }}" class="size-6">
                            </span>
                            Mon Véhicule
                        </h2>
                        <a href="{{ route('vehicule.create') }}" class="text-sm bg-vert-principale text-white px-3 py-1.5 rounded-lg hover:bg-vert-principal-h transition flex items-center gap-1"><span>+</span> Ajouter</a>
                    </div>
                    <p class="text-sm text-gris1 mb-6">Informations sur votre véhicule pour les trajets en tant que conducteur</p>

                    <div class="space-y-4">
                        @forelse($user->vehicules as $vehicule)
                            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4 relative group hover:border-vert-principale/50 transition-colors">
                                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                    <form action="{{ route('vehicule.destroy', $vehicule->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce véhicule ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 p-1.5 bg-white rounded-full shadow-sm hover:shadow-md transition-all" title="Supprimer le véhicule">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                                <div><p class="text-xs text-gris1 uppercase tracking-wide">Marque et Modèle</p><p class="font-semibold text-noir mt-1">{{ $vehicule->marque }} {{ $vehicule->modele }}</p></div>
                                <div><p class="text-xs text-gris1 uppercase tracking-wide">Immatriculation</p><p class="font-semibold text-noir mt-1">{{ $vehicule->immatriculation }}</p></div>
                                <div><p class="text-xs text-gris1 uppercase tracking-wide">Couleur</p><p class="font-semibold text-noir mt-1">{{ $vehicule->couleur }}</p></div>
                                <div><p class="text-xs text-gris1 uppercase tracking-wide">Nombre de places</p><p class="font-semibold text-noir mt-1">{{ $vehicule->nombre_place }} places</p></div>
                            </div> 
                        @empty
                            <div class="bg-gray-50 rounded-xl p-8 border border-dashed border-gray-300 text-center"><p class="text-gris1 italic">Aucun véhicule enregistré pour le moment.</p></div>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('vehicule.create') }}" class="flex items-center gap-1 text-sm font-medium text-vert-principale hover:text-vert-principal-h transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            @if($user->vehicules->count() > 0) Ajouter un autre véhicule @else Ajouter un véhicule @endif
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6 flex items-center gap-2">
                        <span class="w-10 h-10 rounded-full bg-beige-principale flex items-center justify-center text-noir">
                            <img src="{{ asset('images/accueil/icones/preference.png') }}" class="size-4">
                        </span>
                        Préférences de Covoiturage
                    </h2>
                    <div class="space-y-6">
                        
                        <x-preference-row icon="patte" title="Accepter les animaux" subtitle="Autoriser les animaux de compagnie">
                            <form action="{{ route('preference.toggle') }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="field" value="accepte_animaux">
                                
                                <x-toggle name="toggle_animaux" 
                                        :checked="$user->preference?->accepte_animaux" 
                                        onchange="this.form.submit()" />
                            </form>
                        </x-preference-row>

                        <x-preference-row icon="cigarette" title="Fumeur" subtitle="Autoriser de fumer dans le véhicule">
                            <form action="{{ route('preference.toggle') }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="field" value="accepte_fumeurs">
                                
                                <x-toggle name="toggle_fumeurs" 
                                        :checked="$user->preference?->accepte_fumeurs" 
                                        onchange="this.form.submit()" />
                            </form>
                        </x-preference-row>

                        <x-preference-row icon="musique" title="Accepter la musique" subtitle="Autoriser l'écoute de musique dans le véhicule">'">
                            <form action="{{ route('preference.toggle') }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="field" value="accepte_musique">
                                
                                <x-toggle name="toggle_musique" 
                                        :checked="$user->preference?->accepte_musique" 
                                        onchange="this.form.submit()" />
                            </form>
                        </x-preference-row>
                    </div>

                    <form action="{{ route('preference.discussion') }}" method="POST">
                        @csrf @method('PATCH')
                        <x-preference-slider 
                            name="accepte_discussion" 
                            :value="$user->preference?->accepte_discussion ?? 3" 
                            :autosubmit="true" 
                        />
                    </form>
                    
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-noir mb-4">Sécurité</h3>
                    <div class="space-y-3">
                        <a href="{{ route('profile.security') }}" class="block text-gris1 hover:text-vert-principale transition-colors flex justify-between">
                            Changer le mot de passe
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <hr class="border-gray-100">
                        <a href="{{ route('profile.history') }}" class="block text-gris1 hover:text-vert-principale transition-colors flex justify-between">
                            Historique de connexion
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <hr class="border-gray-100">
                        <a href="{{ route('profile.setup') }}" class="block text-gris1 hover:text-vert-principale transition-colors flex justify-between">
                            Paramètres
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
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
    </div>
</div>
@endsection