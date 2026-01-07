@extends('layouts.app')

@section('title', 'Profil de ' . $user->prenom . ' - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="{{ url()->previous() }}" class="text-vert-principale hover:underline font-medium flex items-center gap-1">
                &larr; Retour
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex flex-col items-center text-center">
                    
                    <x-user-avatar :user="$user" class="w-32 h-32 mb-4" textSize="text-4xl" />
                    
                    <h1 class="text-2xl font-bold text-noir">{{ $user->prenom }} {{ $user->nom }}</h1>
                    
                    <p class="text-sm text-gris1 mt-1">
                        Membre depuis {{ $user->created_at ? "le " . $user->created_at->format('d/m/Y') : 'toujours' }}
                    </p>

                    @if($user->preference?->telephone_public && $user->num_tel)
                        <div class="mt-6 w-full p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-xs text-gris1 uppercase tracking-wide mb-1">Téléphone</p>
                            <p class="font-bold text-noir flex items-center justify-center gap-2 text-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-principale" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                                {{ $user->num_tel }}
                            </p>
                        </div>
                    @endif

                    <a href="mailto:{{ $user->email }}" class="mt-4 w-full bg-vert-principale hover:bg-vert-principal-h text-white font-bold py-3 rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>
                        Envoyer un email
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-noir mb-4">Évaluations</h3>
                    
                <div class="flex items-baseline gap-1 mb-4">
                    
                    @if($nombreAvis > 0)
                        <span class="text-4xl font-bold text-noir">
                            {{ $moyenne }}
                        </span>
                        <span class="text-xl text-gray-400 font-light">/5</span>
                    @else
                        <span class="text-xl text-gray-400 font-light">0/5</span>
                    @endif
                    
                    {{-- Étoiles (on garde un petit ml-2 pour l'espacement) --}}
                    <div class="flex text-yellow-400 ml-2 self-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ ($nombreAvis > 0 && $i <= round($moyenne)) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                </div>

                    {{-- Affichage du nombre d'avis --}}
                    <p class="text-sm text-gris1 font-medium bg-gray-50 inline-block px-3 py-1 rounded-full border border-gray-100">
                        @if($nombreAvis > 0)
                            Basé sur {{ $nombreAvis }} avis reçu{{ $nombreAvis > 1 ? 's' : '' }}
                        @else
                            Aucun avis reçu pour le moment
                        @endif
                    </p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6">Préférences de voyage</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        
                        <div class="flex flex-col items-center p-4 rounded-xl border {{ $user->preference?->accepte_animaux ? 'border-vert-principale/30 bg-vert-principale/5' : 'border-gray-100 bg-gray-50 opacity-50' }}">
                            <div class="mb-2 {{ $user->preference?->accepte_animaux ? 'text-vert-principale' : 'text-gray-400' }}">
                                <img src="{{ asset('images/profil/patte.png') }}" class="w-8 h-8 object-contain">
                            </div>
                            <span class="text-sm font-medium {{ $user->preference?->accepte_animaux ? 'text-noir' : 'text-gray-400 decoration-slice' }}">
                                {{ $user->preference?->accepte_animaux ? 'Animaux acceptés' : 'Pas d\'animaux' }}
                            </span>
                        </div>

                        <div class="flex flex-col items-center p-4 rounded-xl border {{ $user->preference?->accepte_fumeurs ? 'border-vert-principale/30 bg-vert-principale/5' : 'border-gray-100 bg-gray-50 opacity-50' }}">
                            <div class="mb-2 {{ $user->preference?->accepte_fumeurs ? 'text-vert-principale' : 'text-gray-400' }}">
                                <img src="{{ asset('images/profil/cigarette.png') }}" class="w-8 h-8 object-contain">
                            </div>
                            <span class="text-sm font-medium {{ $user->preference?->accepte_fumeurs ? 'text-noir' : 'text-gray-400' }}">
                                {{ $user->preference?->accepte_fumeurs ? 'Fumeur accepté' : 'Non fumeur' }}
                            </span>
                        </div>

                        <div class="flex flex-col items-center p-4 rounded-xl border {{ $user->preference?->accepte_musique ? 'border-vert-principale/30 bg-vert-principale/5' : 'border-gray-100 bg-gray-50 opacity-50' }}">
                            <div class="mb-2 {{ $user->preference?->accepte_musique ? 'text-vert-principale' : 'text-gray-400' }}">
                                <img src="{{ asset('images/profil/musique.png') }}" class="w-8 h-8 object-contain">
                            </div>
                            <span class="text-sm font-medium {{ $user->preference?->accepte_musique ? 'text-noir' : 'text-gray-400' }}">
                                {{ $user->preference?->accepte_musique ? 'Musique OK' : 'Pas de musique' }}
                            </span>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6">Véhicule</h2>
                    
                    @forelse($user->vehicules as $vehicule)
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 bg-gray-50">
                            <div class="p-3 bg-white rounded-full shadow-sm">
                                <img src="{{ asset('images/profil/voiture.png') }}" class="w-8 h-8 object-contain">
                            </div>
                            <div>
                                <p class="font-bold text-noir text-lg">{{ $vehicule->marque }} {{ $vehicule->modele }}</p>
                                <p class="text-sm text-gris1">{{ $vehicule->couleur }} • {{ $vehicule->nombre_place }} places</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gris1 italic text-center py-4">Cet utilisateur n'a pas renseigné de véhicule.</p>
                    @endforelse
                </div>


                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="font-bold text-noir text-sm mb-4 uppercase tracking-wider">Logistique</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        {{-- Détour --}}
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-vert-principale shadow-sm">
                                <img src="{{ asset('images/profil/publique/detour.png') }}" class="w-5 h-5 object-contain">
                            </div>
                            <div>
                                <p class="text-xs text-gris1 font-medium">Détour max accepté</p>
                                <p class="font-bold text-noir">
                                    {{ $user->preference?->max_detour ?? 5 }} minutes
                                </p>
                            </div>
                        </div>

                        {{-- Attente --}}
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-vert-principale shadow-sm">
                                <img src="{{ asset('images/profil/publique/retard.png') }}" class="w-5 h-5 object-contain">
                            </div>
                            <div>
                                <p class="text-xs text-gris1 font-medium">Attente retardataire</p>
                                <p class="font-bold text-noir">
                                    {{ $user->preference?->max_attente ?? 5 }} minutes
                                </p>
                            </div>
                        </div>

                    </div>
                </div>



            </div>
        </div>
    </div>
</div>
@endsection