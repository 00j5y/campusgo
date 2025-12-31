@extends('layouts.app')

@section('title', 'Mes Trajets - Campus\'GO')

@section('content')
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <main class="bg-white min-h-screen py-12 relative">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
            
            {{--Header--}}
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-[#333] mb-2">Mes Trajets</h1>
                <p class="text-gray-500">Gérez vos covoiturages passés et à venir.</p>
            </div>

            {{--Notifications--}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

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
    
    {{--ANNULER --}}
    <div id="modal-annuler" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title-annuler" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="window.closeModal('modal-annuler')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Annuler le trajet ?</h3>
                <p class="text-gray-500 text-sm mb-6">Êtes-vous sûr de vouloir annuler ce trajet ?</p>
                <form id="form-annuler" action="" method="POST" class="flex gap-3 justify-center w-full">
                    @csrf 
                    <button type="button" onclick="window.closeModal('modal-annuler')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-bold transition">Retour</button>
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-bold transition shadow-lg">Confirmer</button>
                </form>
            </div>
        </div>
    </div>

    <div id="mapbox-config" class="hidden" data-token="pk.eyJ1IjoiZ2FieXNjb3RlIiwiYSI6ImNtaXlueXBycDBlMnIzZnM3NDF0aWZ4emIifQ.Kv51hN4zyQ9O2AZLlbSdZg"></div>

    {{--SCRIPTS--}}
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.js"></script>
    
    @vite(['resources/js/historique-trajet.js'])

@endsection