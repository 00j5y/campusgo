@extends('layouts.app')

@section('title', 'Rechercher un trajet - Campus\'GO')

@section('content')
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <main class="bg-white min-h-screen py-12 relative">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-semibold text-gris1 mb-2">
                    Salut {{ $prenom }} ! Où voulez vous partir aujourd'hui ?
                </h1>
                <p class="text-gris1">Trouvez un covoiturage en quelques clics.</p>
            </div>

            {{-- Notifications --}}
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

            {{-- Formulaire de recherche --}}
            <div class="bg-white rounded-3xl shadow-xl p-8 max-w-4xl mx-auto mb-12 relative z-10">
                <form id="form-recherche"
                      action="{{ route('rechercher') }}"
                      method="GET"
                      autocomplete="off"
                      class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <input type="hidden" id="coords_depart" name="coords_depart" value="{{ request('coords_depart') }}">
                    <input type="hidden" id="coords_arrivee" name="coords_arrivee" value="{{ request('coords_arrivee') }}">

                    {{-- Départ --}}
                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="font-bold text-noir text-sm mb-2 block">Départ</label>
                        <i class="fa-solid fa-location-dot absolute left-4 top-[2.8rem] text-vert-principale z-10"></i>
                        <input type="text" id="depart" name="depart"
                               value="{{ request('depart') }}"
                               autocomplete="off"
                               placeholder="Ville de départ..."
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-vert-principale focus:outline-none">
                        <ul id="liste-depart" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50 rounded-lg"></ul>
                        <p id="error-depart" class="text-red-500 text-xs mt-1 ml-1 hidden"></p>
                    </div>

                    {{-- Bouton inverser --}}
                    <div class="col-span-1 md:col-span-2 flex justify-center -my-4 relative z-20">
                        <button type="button" id="btn-inverser-recherche"
                                class="bg-white border border-gray-200 hover:bg-gray-50 text-vert-principale shadow-sm w-10 h-10 rounded-full transition flex items-center justify-center">
                            <i class="fa-solid fa-arrow-right-arrow-left rotate-90"></i>
                        </button>
                    </div>

                    {{-- Arrivée --}}
                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="font-bold text-noir text-sm mb-2 block">Arrivée</label>
                        <i class="fa-solid fa-location-crosshairs absolute left-4 top-[2.8rem] text-vert-principale z-10"></i>
                        <input type="text" id="arrivee" name="arrivee"
                               value="{{ request('arrivee') }}"
                               autocomplete="off"
                               placeholder="Ville d'arrivée..."
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-vert-principale focus:outline-none">
                        <ul id="liste-arrivee" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50 rounded-lg"></ul>
                        <p id="error-arrivee" class="text-red-500 text-xs mt-1 ml-1 hidden"></p>
                    </div>

                    {{-- Date & Heure --}}
                    <div>
                        <label class="font-bold text-noir text-sm mb-2 block">Date</label>
                        <input type="text" id="date" name="date"
                               value="{{ request('date') }}"
                               placeholder="Sélectionner une date"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4">
                    </div>

                    <div>
                        <label class="font-bold text-noir text-sm mb-2 block">Heure (optionnel)</label>
                        <input type="text" id="heure" name="heure"
                               value="{{ request('heure') }}"
                               placeholder="Saisissez une heure"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4">
                    </div>

                    {{-- Carte --}}
                    <div class="col-span-1 md:col-span-2 mt-4">
                        <div id="map"
                             class="w-full h-64 rounded-xl border border-gray-200"
                             data-token="pk.eyJ1IjoiZ2FieXNjb3RlIiwiYSI6ImNtaXlueXBycDBlMnIzZnM3NDF0aWZ4emIifQ.Kv51hN4zyQ9O2AZLlbSdZg"
                             data-center="[2.263592, 49.873836]">
                        </div>
                    </div>

                    {{-- Bouton rechercher --}}
                    <div class="col-span-1 md:col-span-2">
                        <button type="submit"
                                class="w-full bg-vert-principale hover:bg-[#1b5e20] text-white font-bold py-3 rounded-xl transition shadow-md">
                            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>

            {{-- Résultats --}}
            {{-- (le reste de ton code est déjà propre, rien à changer) --}}

        </div>
    </main>

    @vite(['resources/js/recherche.js'])
@endsection
