@extends('layouts.app')

@section('title', 'Historique des avis - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12" x-data="{ tab: 'recus' }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-noir">Historique des avis</h1>
                    <p class="text-gris1 mt-2">Gérez les avis que vous avez reçus et donnés</p>
                </div>
                <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium">
                    &larr; Retour au profil
                </a>
            </div>

            {{-- BOUTONS D'ONGLETS --}}
            <div class="flex p-1 space-x-1 bg-white rounded-xl border border-gray-200 w-fit shadow-sm">
                <button @click="tab = 'recus'"
                        :class="tab === 'recus' ? 'bg-vert-principale text-white shadow' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="px-6 py-2.5 text-sm font-bold rounded-lg transition-all duration-200">
                    Avis Reçus ({{ $total }})
                </button>
                <button @click="tab = 'emis'"
                        :class="tab === 'emis' ? 'bg-vert-principale text-white shadow' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="px-6 py-2.5 text-sm font-bold rounded-lg transition-all duration-200">
                    Avis Émis ({{ $avisEmis->count() }})
                </button>
            </div>
        </div>

        <div class="max-w-3xl mx-auto space-y-8">

            {{-- ONGLET 1 : AVIS REÇUS --}}
            <div x-show="tab === 'recus'" x-transition:enter="transition ease-out duration-300">

                {{-- STATISTIQUES (Moyenne) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center relative overflow-hidden mb-8">
                    <div class="absolute top-0 left-0 w-full h-1 bg-vert-principale"></div>
                    
                    <p class="text-gris1 uppercase text-xs font-bold tracking-widest mb-2">Note Globale</p>
                    
                    <div class="flex justify-center items-baseline gap-2 mb-4">
                        <span class="text-6xl font-bold text-noir">{{ $average }}</span>
                        <span class="text-gray-400 text-2xl font-light">/5</span>
                    </div>
                    
                    <div class="flex justify-center gap-1 mb-2 text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $i <= round($average) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    
                    <p class="text-sm text-gris1 font-medium bg-gray-50 inline-block px-3 py-1 rounded-full border border-gray-100">
                        Basé sur {{ $total }} avis reçus
                    </p>
                </div>

                {{-- LISTE DES AVIS REÇUS --}}
                <div class="space-y-4">
                    @forelse($avisRecus as $review)
                        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm transition hover:shadow-md">
                            <div class="flex gap-4">
                                
                                @php $estAnonyme = ($review->id_auteur == 999); @endphp

                                {{-- 1. AVATAR --}}
                                <div class="shrink-0">
                                    {{-- CAS 1 : ANONYME (Priorité absolue) --}}
                                    @if($estAnonyme)
                                        <div class="w-12 h-12 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center border border-transparent">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>

                                    {{-- CAS 2 : UTILISATEUR AVEC PHOTO --}}
                                    {{-- On suppose que le chemin est stocké complet ou dans 'storage/' --}}
                                    @elseif($review->auteur && $review->auteur->photo)
                                        <img src="{{ asset('storage/' . $review->auteur->photo) }}" 
                                            alt="{{ $review->auteur->prenom }}" 
                                            class="w-12 h-12 rounded-full object-cover border border-gray-200">

                                    {{-- CAS 3 : PAS DE PHOTO (On affiche les initiales) --}}
                                    @else
                                        <div class="w-12 h-12 bg-vert-principale/10 text-vert-principale rounded-full flex items-center justify-center font-bold text-lg border border-transparent">
                                            {{ $review->auteur ? substr($review->auteur->prenom, 0, 1) : '?' }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        {{-- NOM --}}
                                        <h3 class="font-bold text-noir text-lg">
                                            @if($estAnonyme)
                                                <span class="italic text-gray-500">Utilisateur Anonyme</span>
                                            @else
                                                {{ $review->auteur ? $review->auteur->prenom : 'Utilisateur supprimé' }}
                                            @endif
                                        </h3>
                                        
                                        <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-md border border-gray-100">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    @if($review->trajet)
                                        <div class="mb-2 inline-flex items-center gap-2 bg-gray-50 px-2.5 py-1 rounded-md text-xs text-gray-600 border border-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-vert-principale" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                            <span class="font-bold">{{ $review->trajet->lieu_depart }} &rarr; {{ $review->trajet->lieu_arrivee }}</span>
                                            <span class="text-gray-300">|</span>
                                            <span>{{ \Carbon\Carbon::parse($review->trajet->date_depart)->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                    
                                    {{-- ÉTOILES --}}
                                    <div class="flex text-yellow-400 text-sm mb-3">
                                        @for($i = 0; $i < 5; $i++)
                                            <span>{{ $i < $review->note ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                    
                                    {{-- COMMENTAIRE --}}
                                    <p class="text-gray-600 leading-relaxed">
                                        {{ $review->commentaire }}
                                    </p>

                                    {{-- LIEN SIGNALER --}}
                                    <div class="mt-4 flex justify-end">
                                        <button class="text-xs text-gray-400 hover:text-red-500 hover:underline flex items-center gap-1 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Signaler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-500 font-medium">Vous n'avez pas encore reçu d'avis.</p>
                            <p class="text-sm text-gray-400 mt-1">Les avis apparaîtront ici une fois vos premiers trajets effectués.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- ONGLET 2 : AVIS ÉMIS --}}
            <div x-show="tab === 'emis'" x-cloak x-transition:enter="transition ease-out duration-300">
                
                {{-- INFO ANONYME --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex gap-3 text-sm text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>Pour garantir votre confidentialité, les avis que vous avez publiés de manière <strong>anonyme</strong> ne sont pas listés ici.</p>
                </div>

                {{-- LISTE DES AVIS ÉMIS --}}
                <div class="space-y-4">
                    @forelse($avisEmis as $review)
                        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm relative group transition hover:shadow-md">
                            
                            <div class="flex gap-4 items-center">
                                
                                <div class="shrink-0">
                                    {{-- CAS 1 : UTILISATEUR AVEC PHOTO --}}
                                    @if($review->destinataire && $review->destinataire->photo)
                                        <img src="{{ asset('storage/' . $review->destinataire->photo) }}" 
                                            alt="{{ $review->destinataire->prenom }}" 
                                            class="w-12 h-12 rounded-full object-cover border border-gray-200">
                                    
                                    {{-- CAS 2 : PAS DE PHOTO (Initiales) --}}
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center font-bold text-lg text-gray-600">
                                            {{ $review->destinataire ? substr($review->destinataire->prenom, 0, 1) : '?' }}
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <div>
                                            <span class="text-xs text-gray-400 block">Pour :</span>
                                            <h3 class="font-bold text-noir text-lg">
                                                {{ $review->destinataire ? $review->destinataire->prenom . ' ' . $review->destinataire->nom : 'Utilisateur supprimé' }}
                                            </h3>
                                        </div>
                                        {{-- La date reste en haut à droite du bloc texte --}}
                                        <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-md border border-gray-100">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    @if($review->trajet)
                                        <div class="mb-2 inline-flex items-center gap-2 bg-gray-50 px-2.5 py-1 rounded-md text-xs text-gray-600 border border-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-vert-principale" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                            <span class="font-bold">{{ $review->trajet->lieu_depart }} &rarr; {{ $review->trajet->lieu_arrivee }}</span>
                                            <span class="text-gray-300">|</span>
                                            <span>{{ \Carbon\Carbon::parse($review->trajet->date_depart)->format('d/m/Y') }}</span>
                                        </div>
                                    @endif

                                    <div class="flex text-yellow-400 text-sm mb-1">
                                        @for($i = 0; $i < 5; $i++) 
                                            <span>{{ $i < $review->note ? '★' : '☆' }}</span> 
                                        @endfor
                                    </div>
                                    
                                    <p class="text-gray-600 italic text-sm">{{ $review->commentaire }}</p>
                                </div>

                                {{-- 3. BOUTON SUPPRIMER --}}
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity pl-2">
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment retirer cet avis définitivement ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 bg-white p-2 rounded-full border border-gray-200 hover:bg-red-50 hover:border-red-100 transition-all shadow-sm" title="Supprimer cet avis">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                            <p class="text-gray-500">Vous n'avez publié aucun avis publiquement.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection