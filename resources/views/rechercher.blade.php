@extends('layouts.app')

@section('title', 'Rechercher un trajet - Campus\'GO')

@section('content')
<link href="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<main class="bg-white min-h-screen py-12 relative">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        {{--EN-TÊTE & NOTIFICATIONS--}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-semibold text-gris1 mb-2">Salut {{ $prenom }} ! Où voulez vous partir aujourd'hui ?</h1>
            <p class="text-gris1">Trouvez un covoiturage en quelques clics.</p>
        </div>

        @if(session('success'))
            <div class="max-w-4xl mx-auto mb-6 bg-green-100 border border-green-400 text-vert-principale px-4 py-3 rounded relative">
                <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-4xl mx-auto mb-6 bg-red-100 border border-rouge text-rouge px-4 py-3 rounded relative">
                <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
            </div>
        @endif

        {{--FORMULAIRE DE RECHERCHE--}}
        <div class="bg-white rounded-3xl shadow-xl p-8 max-w-4xl mx-auto mb-12 relative z-10">
            <form id="form-recherche" action="{{ route('rechercher') }}" method="GET" autocomplete="off" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Champs cachés pour les coordonnées GPS --}}
                <input type="hidden" id="coords_depart" name="coords_depart" value="{{ request('coords_depart') }}">
                <input type="hidden" id="coords_arrivee" name="coords_arrivee" value="{{ request('coords_arrivee') }}">
                
                {{--DEPART--}}
                <div class="col-span-1 md:col-span-2 relative">
                    <label class="font-bold text-noir text-sm mb-2 block">Départ</label>
                    <i class="fa-solid fa-location-dot absolute left-4 top-[2.8rem] text-vert-principale z-10"></i>
                    <input type="text" id="depart" name="depart" value="{{ request('depart') }}" autocomplete="off" placeholder="Ville de départ..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-vert-principale focus:outline-none focus:ring-1 focus:ring-vert-principale relative z-0">
                    <ul id="liste-depart" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50 rounded-lg"></ul>
                    
                    <p id="error-depart" class="text-red-500 text-xs mt-1 ml-1 hidden"></p>
                </div>

                {{--Bouton d'inversion--}}
                <div class="col-span-1 md:col-span-2 flex justify-center -my-4 relative z-20">
                    <button type="button" id="btn-inverser-recherche" class="bg-white border border-gray-200 hover:bg-gray-50 text-vert-principale shadow-sm w-10 h-10 rounded-full transition cursor-pointer flex items-center justify-center">
                        <i class="fa-solid fa-arrow-right-arrow-left rotate-90"></i>
                    </button>
                </div>

                {{--ARRIVEE--}}
                <div class="col-span-1 md:col-span-2 relative">
                    <label class="font-bold text-noir text-sm mb-2 block">Arrivée</label>
                    <i class="fa-solid fa-location-crosshairs absolute left-4 top-[2.8rem] text-vert-principale z-10"></i>
                    <input type="text" id="arrivee" name="arrivee" value="{{ request('arrivee') }}" autocomplete="off" placeholder="Ville d'arrivée..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-vert-principale focus:outline-none focus:ring-1 focus:ring-vert-principale relative z-0">
                    <ul id="liste-arrivee" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50 rounded-lg"></ul>

                    <p id="error-arrivee" class="text-red-500 text-xs mt-1 ml-1 hidden"></p>
                </div>

                {{--DATE ET HEURE--}}
                <div>
                    <label class="font-bold text-noir text-sm mb-2 block">Date</label>
                    <div class="relative">
                        <input type="text" id="date" name="date" value="{{ request('date') }}" placeholder="Sélectionner une date" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:border-vert-principale focus:outline-none cursor-pointer">
                        <i class="fa-regular fa-calendar absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="font-bold text-noir text-sm mb-2 block">Horaire</label>
                    <div class="flex w-full relative z-30 h-[50px]">
                        
                        <div class="relative h-full" id="custom-select-container">
                            <input type="hidden" name="type_horaire" id="type_horaire_input" value="{{ request('type_horaire', 'depart') }}">
                            
                            <button type="button" id="dropdown-trigger" class="h-full bg-gray-50 border border-r-0 border-gray-200 rounded-l-xl pl-4 pr-8 text-sm focus:border-vert-principale focus:ring-1 focus:ring-vert-principale focus:outline-none cursor-pointer text-gray-700 flex items-center min-w-[110px] transition-all">
                                <span id="dropdown-label">{{ request('type_horaire') == 'arrivee' ? 'Arrivée' : 'Départ' }}</span>
                                <i class="fa-solid fa-chevron-down text-xs absolute right-3 text-gray-400"></i>
                            </button>
            
                            <div id="dropdown-menu" class="hidden absolute top-full left-0 mt-2 w-full bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden animate-fade-in-down">
                                <div class="dropdown-option px-4 py-3 text-sm text-gray-700 hover:bg-[#2E7D32] hover:text-white cursor-pointer transition-colors" data-value="depart">Départ</div>
                                <div class="dropdown-option px-4 py-3 text-sm text-gray-700 hover:bg-[#2E7D32] hover:text-white cursor-pointer transition-colors" data-value="arrivee">Arrivée</div>
                            </div>
                        </div>
                        
                        <div class="relative w-full flex-1 h-full">
                            <input type="text" id="heure" name="heure" value="{{ request('heure') }}" placeholder="Sélectionner une heure" class="w-full h-full bg-gray-50 border border-gray-200 rounded-r-xl  px-4 focus:border-vert-principale focus:outline-none cursor-pointer">
                            <i class="fa-regular fa-clock absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                {{--CARTE MAPBOX--}}
                <div class="col-span-1 md:col-span-2 mt-4">
                    <div id="map" 
                        class="w-full h-64 rounded-xl border border-gray-200 z-0"
                        data-token="pk.eyJ1IjoiZ2FieXNjb3RlIiwiYSI6ImNtaXlueXBycDBlMnIzZnM3NDF0aWZ4emIifQ.Kv51hN4zyQ9O2AZLlbSdZg"
                        data-center="[2.263592, 49.873836]">
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <button type="submit" class="cursor-pointer w-full bg-vert-principale hover:bg-[#1b5e20] text-white font-bold py-3 rounded-xl transition shadow-md flex justify-center items-center gap-2 transform active:scale-95 relative z-20">
                        <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>

        {{--RESULTATS DE LA RECHERCHE--}}
        @if($rechercheFaite)
            <div class="max-w-4xl mx-auto mb-16 animate-fade-in-up">
                <h2 class="text-xl font-bold text-[#333] mb-6 flex items-center gap-2">
                    <span>Trajets disponibles</span>
                    <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">{{ $resultats->count() }}</span>
                </h2>
                
                @if($resultats->isEmpty())
                    <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                        <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-magnifying-glass text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-[#333]">Aucun trajet trouvé</h3>
                        <p class="text-gray-500 mt-2 max-w-md mx-auto">Nous n'avons pas trouvé de trajet correspondant à vos critères. Essayez de modifier vos paramètres de recherche ou proposez votre propre trajet !</p>
                        <a href="{{ route('trajets.create') }}" class="mt-6 inline-block bg-vert-principale text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-[#1b5e20] transition">Proposer un trajet</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($resultats as $trajet)
                            @include('components.trajet-card', ['trajet' => $trajet, 'mode' => 'search'])
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{--MES PROCHAINS TRAJETS--}}

        <div class="max-w-4xl mx-auto">
            <div class="mb-6 flex justify-between items-end border-b pb-2">
                <h2 class="text-xl font-bold text-[#333]">Vos Prochains Trajets</h2>
                @if($mesTrajets->count() > 1)
                    <button id="btn-voir-tout" onclick="toggleVoirTout()" class="cursor-pointer text-sm text-vert-principale font-semibold hover:underline bg-transparent border-0">
                            Voir tout ({{ $mesTrajets->count() }})
                    </button>
                @endif
            </div>

            @if($mesTrajets->isEmpty())
                <div class="text-center py-8 bg-white rounded-2xl border border-gray-100 text-gray-500 shadow-sm">
                    <p>Aucun trajet prévu pour le moment.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($mesTrajets as $trajet)
                        <div class="transition-all duration-300 {{ $loop->iteration > 1 ? 'hidden trajet-cache' : '' }}">
                            @include('components.trajet-card', ['trajet' => $trajet, 'mode' => 'perso'])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        {{-- Footer Call-to-Action --}}
        <div class="bg-[#E0E5D5] rounded-3xl p-8 mt-16 text-center">
            <h3 class="font-bold text-[#333] text-lg mb-2">Prêt à partir ?</h3>
            <div class="flex justify-center gap-4 mt-4">
                <a href="{{ route('trajets.create') }}" class="bg-vert-principale text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-[#1b5e20] transition">Proposer</a>
                <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" class="cursor-pointer bg-white text-[#333] px-6 py-2.5 rounded-lg font-bold text-sm border hover:bg-gray-50 transition">Rechercher</button>
            </div>
            <h4 class="text-gray-500 text-sm mt-4">Rejoignez la communauté Campus'Go et rendez vos trajets plus agréables.</h4>
        </div>
    </div>
</main>


{{-- Modale Réservation --}}
<div id="modal-reserver" class="fixed inset-0 hidden z-50" aria-labelledby="modal-title-reserver" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-reserver')"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center transform transition-all scale-100">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <i class="fa-solid fa-check text-2xl text-vert-principale"></i>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Confirmer la réservation</h3>
            <p class="text-gray-500 text-sm mb-6">Voulez-vous réserver ce trajet ?</p>
            
            <form id="form-reserver" action="" method="POST" class="flex gap-3 justify-center w-full">
                @csrf 
                <button type="button" onclick="closeModal('modal-reserver')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-bold transition">Retour</button>
                <button type="submit" class="flex-1 bg-vert-principale hover:bg-[#1b5e20] text-white px-4 py-3 rounded-xl font-bold transition shadow-lg">Réserver</button>
            </form>
        </div>
    </div>
</div>

{{-- Modale Annulation --}}
<div id="modal-annuler" class="fixed inset-0 hidden z-50" aria-labelledby="modal-title-annuler" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-annuler')"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center transform transition-all scale-100">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Annuler le trajet ?</h3>
            <p class="text-gray-500 text-sm mb-6">Êtes-vous sûr de vouloir continuer cette action ?</p>
            
            <form id="form-annuler" action="" method="POST" class="flex gap-3 justify-center w-full">
                @csrf 
                <button type="button" onclick="closeModal('modal-annuler')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-bold transition">Retour</button>
                <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-bold transition shadow-lg">Confirmer</button>
            </form>
        </div>
    </div>
</div>

<script src="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
@vite(['resources/js/recherche.js'])
@endsection