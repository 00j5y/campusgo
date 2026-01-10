@extends('layouts.app')

@section('title', 'Mes Trajets - Campus\'GO')

@section('content')
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <main class="bg-gray-50 min-h-screen py-12 relative">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            
            {{--Header--}}
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-[#333] mb-2">Mes Trajets</h1>
                <p class="text-gray-500">Gérez vos covoiturages passés et à venir.</p>
            </div>

            {{--SYSTÈME D'ONGLETS--}}
            <div class="bg-gray-200 p-1 rounded-xl flex mb-8">
                <button id="btn-avenir" onclick="window.changerOnglet('avenir')" class="cursor-pointer flex-1 py-2 rounded-lg font-bold text-sm transition-all bg-white text-[#2E7D32] shadow-sm">
                    <i class="fa-regular fa-calendar-days mr-2"></i> Trajets à Venir
                </button>
                <button id="btn-passe" onclick="window.changerOnglet('passe')" class="cursor-pointer flex-1 py-2 rounded-lg font-bold text-sm text-gray-500 hover:text-[#333] transition-all">
                    <i class="fa-solid fa-clock-rotate-left mr-2"></i> Trajets Passés
                </button>
            </div>

            {{--TRAJETS À VENIR--}}
            <div id="content-avenir" class="space-y-4 animate-fade-in">
                @forelse($trajetsAvenir as $trajet)
                    @include('components.trajet-card', ['trajet' => $trajet, 'mode' => 'perso', 'etat' => 'avenir'])
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                        <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Aucun trajet à venir pour le moment.</p>
                        <a href="{{ route('rechercher') }}" class="text-[#2E7D32] font-bold hover:underline mt-2 inline-block">Rechercher un trajet</a>
                    </div>
                @endforelse
            </div>

            {{--TRAJETS PASSÉS--}}
            <div id="content-passe" class="space-y-4 hidden animate-fade-in">
                @forelse($trajetsPasses as $trajet)
                    @include('components.trajet-card', ['trajet' => $trajet, 'mode' => 'perso', 'etat' => 'passe'])
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-300">
                        <i class="fa-solid fa-history text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Vous n'avez pas encore d'historique de trajets.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </main>
    
    <x-popup 
        id="modal-delete-trip"
        title="Supprimer le trajet ?"
        message="Êtes-vous sûr de vouloir supprimer ce trajet ? Cette action est irréversible."
        type="danger" 
        confirmText="Oui, supprimer"
        method="DELETE"
    />

    {{-- Modale Annulation (Passager) --}}
    <x-popup 
        id="modal-cancel-reservation"
        title="Annuler la réservation ?"
        message="Voulez-vous vraiment annuler votre place sur ce trajet ?"
        type="danger" 
        confirmText="Oui, annuler"
    />

    <div id="mapbox-config" class="hidden" data-token="pk.eyJ1IjoiZ2FieXNjb3RlIiwiYSI6ImNtaXlueXBycDBlMnIzZnM3NDF0aWZ4emIifQ.Kv51hN4zyQ9O2AZLlbSdZg"></div>

    {{--SCRIPTS--}}
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.js"></script>
    
    @vite(['resources/js/historique-trajet.js'])

@endsection