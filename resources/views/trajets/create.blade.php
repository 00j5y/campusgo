@extends('layouts.app')

@section('title', 'Proposer un trajet - Campus\'GO')

@section('content')

{{-- Dépendances Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 max-w-4xl">

    {{-- En-tête --}}
    <header class="mb-10 mt-6 text-center">
        <h1 class="text-3xl font-semibold text-noir">Proposez votre covoiturage IUT</h1>
        <p class="mt-2 text-gris1">Remplissez les informations ci-dessous pour publier votre trajet</p>
    </header>

    {{-- Affichage des erreurs --}}
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-md shadow-sm">
            <h3 class="text-sm font-medium text-red-800 mb-2">
                Il y a {{ $errors->count() }} erreur(s) dans votre formulaire :
            </h3>
            <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="form-creation" method="POST" action="{{ route('trajets.store') }}">
        @csrf

        {{-- Réutilisation du dernier trajet --}}
        <div class="bg-[#fcfaf8] border border-beige-second/50 rounded-lg mb-8 p-6">
            <h2 class="text-lg font-semibold text-noir mb-2">Réutiliser le trajet précédent</h2>
            <p class="text-gris1 mb-3 text-sm">
                Copiez rapidement les informations d’un trajet déjà effectué
            </p>

            @isset($dernierTrajet)
                <div
                    id="dernier-trajet-data"
                    js-depart="{{ $dernierTrajet->lieu_depart }}"
                    js-arrivee="{{ $dernierTrajet->lieu_arrivee }}"
                    js-date="{{ $dernierTrajet->date_depart }}"
                    js-heure="{{ \Carbon\Carbon::parse($dernierTrajet->heure_depart)->format('H:i') }}"
                    js-places="{{ $dernierTrajet->place_disponible }}"
                    js-vehicule="{{ $dernierTrajet->id_vehicule }}"
                    class="bg-white border border-gray-200 rounded-lg p-4 flex justify-between items-center"
                >
                    <div class="text-sm">
                        <p class="font-semibold text-gris1">De : {{ $dernierTrajet->lieu_depart }}</p>
                        <p class="font-semibold text-gris1 ml-4">À : {{ $dernierTrajet->lieu_arrivee }}</p>
                        <p class="text-xs text-gris1 mt-2">
                            {{ \Carbon\Carbon::parse($dernierTrajet->heure_depart)->format('H:i') }} —
                            {{ $dernierTrajet->place_disponible }} place(s)
                        </p>
                    </div>

                    <button type="button" id="btn-utiliser"
                        class="bg-vert-principale text-white px-4 py-2 rounded-md hover:bg-vert-principal-h transition">
                        Utiliser
                    </button>
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <p class="text-sm text-gris1">Aucun trajet enregistré</p>
                </div>
            @endisset
        </div>

        {{-- Détails du trajet --}}
        <div class="bg-white border border-gray-200 rounded-lg mb-8 p-6">
            <h2 class="text-lg font-semibold text-noir mb-4">Détails du trajet</h2>

            <input type="hidden" id="coords_depart" name="coords_depart">
            <input type="hidden" id="coords_arrivee" name="coords_arrivee">

            {{-- Départ --}}
            <div class="mb-6 relative">
                <label for="lieu_depart" class="block text-sm font-medium text-noir mb-1">Lieu de départ</label>
                <input type="text" id="lieu_depart" name="lieu_depart" autocomplete="off"
                    class="w-full border border-gray-300 rounded-md p-3" required>
                <ul id="liste-depart"
                    class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto hidden z-50 rounded-lg"></ul>
                <p id="error-lieu-depart" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            {{-- Inversion --}}
            <div class="flex justify-center mb-4">
                <button type="button" id="btn-inverser-lieux"
                    class="bg-white border w-10 h-10 rounded-full shadow hover:bg-gray-50">
                    ↕
                </button>
            </div>

            {{-- Arrivée --}}
            <div class="mb-6 relative">
                <label for="lieu_arrivee" class="block text-sm font-medium text-noir mb-1">Lieu d’arrivée</label>
                <input type="text" id="lieu_arrivee" name="lieu_arrivee" autocomplete="off"
                    class="w-full border border-gray-300 rounded-md p-3" required>
                <ul id="liste-arrivee"
                    class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto hidden z-50 rounded-lg"></ul>
                <p id="error-lieu-arrivee" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>

            {{-- Date & heure --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <input type="text" id="date_depart" name="date_depart" placeholder="Date"
                    class="w-full bg-gray-50 border rounded-xl p-3" required>
                <input type="text" id="heure_depart" name="heure_depart" placeholder="Heure"
                    class="w-full bg-gray-50 border rounded-xl p-3" required>
            </div>

            {{-- Places --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-noir mb-1">Places disponibles</label>
                <select id="places_disponibles" name="places_disponibles"
                    class="w-full border border-gray-300 rounded-md p-3" required>
                    <option value="" disabled selected>Sélectionnez</option>
                    @for ($i = 1; $i <= 7; $i++)
                        <option value="{{ $i }}">{{ $i }} place(s)</option>
                    @endfor
                </select>
            </div>

            {{-- Véhicule --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-noir mb-1">Véhicule utilisé</label>

                @if ($vehicules->isEmpty())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                        <p class="text-red-600 text-sm mb-3">Aucun véhicule enregistré</p>
                        <a href="{{ route('vehicule.create', ['source' => 'trajet']) }}"
                           onclick="saveFormData()"
                           class="bg-red-500 text-white px-5 py-2 rounded-lg">
                            Ajouter un véhicule
                        </a>
                    </div>
                @else
                    <select id="vehicule_id" name="id_vehicule"
                        class="w-full bg-gray-50 border rounded-md p-3" required>
                        <option value="" disabled selected>Sélectionnez un véhicule</option>
                        @foreach ($vehicules as $vehicule)
                            <option value="{{ $vehicule->id }}"
                                {{ old('id_vehicule') == $vehicule->id ? 'selected' : '' }}>
                                {{ $vehicule->marque }} {{ $vehicule->modele }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-6">
                <button type="submit"
                    class="bg-vert-principale text-white px-6 py-2 rounded-md hover:bg-vert-principal-h">
                    Publier le trajet
                </button>
                <a href="{{ url('/') }}"
                    class="bg-white text-gris1 px-6 py-2 rounded-md hover:bg-gray-50">
                    Annuler
                </a>
            </div>
        </div>
    </form>
</main>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
@vite(['resources/js/proposer.js'])

@endsection
