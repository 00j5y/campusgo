@extends('layouts.app')

@section('title', 'Profil de ' . $user->firstname . ' - Campus\'GO')

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
                    
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-beige-principale flex items-center justify-center mb-4">
                        @if($user->Photo)
                            <img src="{{ asset('storage/' . $user->Photo) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/accueil/icones/personne-convivialite-vert.png') }}" class="w-16 h-16 object-contain">
                        @endif
                    </div>
                    
                    <h1 class="text-2xl font-bold text-noir">{{ $user->firstname }} {{ $user->lastname }}</h1>
                        <p class="text-sm text-gris1 mt-1">
                            Membre depuis {{ $user->created_at ? $user->created_at->format('M Y') : 'toujours' }}
                        </p>
                    
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <span class="inline-flex items-center gap-1 bg-vert-principale/10 px-3 py-1 rounded-full text-vert-principale text-xs font-bold uppercase tracking-wide">
                            Étudiant
                        </span>
                        <span class="inline-flex items-center gap-1 bg-blue-50 px-3 py-1 rounded-full text-blue-600 text-xs font-bold uppercase tracking-wide">
                            Vérifié
                        </span>
                    </div>

                    <a href="mailto:{{ $user->email }}" class="mt-8 w-full bg-vert-principale hover:bg-vert-principal-h text-white font-bold py-3 rounded-xl shadow-sm transition-colors flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>
                        Contacter
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-noir mb-4">Évaluations</h3>
                    <div class="flex items-end gap-2 mb-2">
                        <span class="text-4xl font-bold text-noir">4.8</span>
                        <div class="flex text-yellow-400 mb-1.5 text-lg">★★★★★</div>
                    </div>
                    <p class="text-sm text-gris1">(12 avis reçus)</p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6">Préférences de voyage</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        
                        <div class="flex flex-col items-center p-4 rounded-xl border {{ $user->preference?->Accepte_animaux ? 'border-vert-principale/30 bg-vert-principale/5' : 'border-gray-100 bg-gray-50 opacity-50' }}">
                            <div class="mb-2 {{ $user->preference?->Accepte_animaux ? 'text-vert-principale' : 'text-gray-400' }}">
                                <img src="{{ asset('images/accueil/icones/patte.png') }}" class="w-8 h-8 object-contain">
                            </div>
                            <span class="text-sm font-medium {{ $user->preference?->Accepte_animaux ? 'text-noir' : 'text-gray-400 decoration-slice' }}">
                                {{ $user->preference?->Accepte_animaux ? 'Animaux acceptés' : 'Pas d\'animaux' }}
                            </span>
                        </div>

                        <div class="flex flex-col items-center p-4 rounded-xl border {{ $user->preference?->Accepte_fumeurs ? 'border-vert-principale/30 bg-vert-principale/5' : 'border-gray-100 bg-gray-50 opacity-50' }}">
                            <div class="mb-2 {{ $user->preference?->Accepte_fumeurs ? 'text-vert-principale' : 'text-gray-400' }}">
                                <img src="{{ asset('images/accueil/icones/cigarette.png') }}" class="w-8 h-8 object-contain">
                            </div>
                            <span class="text-sm font-medium {{ $user->preference?->Accepte_fumeurs ? 'text-noir' : 'text-gray-400' }}">
                                {{ $user->preference?->Accepte_fumeurs ? 'Fumeur accepté' : 'Non fumeur' }}
                            </span>
                        </div>

                        <div class="flex flex-col items-center p-4 rounded-xl border {{ $user->preference?->Accepte_musique ? 'border-vert-principale/30 bg-vert-principale/5' : 'border-gray-100 bg-gray-50 opacity-50' }}">
                            <div class="mb-2 {{ $user->preference?->Accepte_musique ? 'text-vert-principale' : 'text-gray-400' }}">
                                <img src="{{ asset('images/accueil/icones/musique.png') }}" class="w-8 h-8 object-contain">
                            </div>
                            <span class="text-sm font-medium {{ $user->preference?->Accepte_musique ? 'text-noir' : 'text-gray-400' }}">
                                {{ $user->preference?->Accepte_musique ? 'Musique OK' : 'Pas de musique' }}
                            </span>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6">Véhicule</h2>
                    
                    @forelse($user->vehicules as $vehicule)
                        <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 bg-gray-50">
                            <div class="p-3 bg-white rounded-full shadow-sm">
                                <img src="{{ asset('images/accueil/icones/voiture.png') }}" class="w-8 h-8 object-contain">
                            </div>
                            <div>
                                <p class="font-bold text-noir text-lg">{{ $vehicule->Marque }} {{ $vehicule->Modele }}</p>
                                <p class="text-sm text-gris1">{{ $vehicule->Couleur }} • {{ $vehicule->NombrePlace }} places</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gris1 italic text-center py-4">Cet utilisateur n'a pas renseigné de véhicule.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>
@endsection