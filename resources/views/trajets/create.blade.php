@extends('layouts.app')

@section('title', 'Proposer un trajet - Campus\'GO')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">

<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 max-w-4xl">
    
    {{-- EN-TÊTE --}}
    <header class="mb-10 mt-6 text-center">
        <h1 class="text-3xl font-semibold text-noir">Proposez votre covoiturage IUT</h1>
        <p class="mt-2 text-gris1">Remplissez les informations ci-dessous pour publier votre trajet</p>
    </header>

    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-md shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Il y a {{ $errors->count() }} erreur(s) dans votre formulaire :
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form id="form-creation" method="POST" action="{{ route('trajets.store') }}">
        @csrf

        {{-- RÉUTILISER LE TRAJET PRÉCÉDENT --}}
        <div class="bg-[#fcfaf8] border border-beige-second/50 rounded-lg mb-8 p-6">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-lg font-semibold text-noir flex items-center">
                    <svg class="h-6 w-6 text-vert-principale mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                    Réutiliser le trajet précédent
                </h2>
            </div>

            <p class="text-gris1 mb-3 text-sm">
                Gagnez du temps en copiant les informations d'un trajet déjà effectué
            </p>

            @isset($dernierTrajet)
                <div id="dernier-trajet-data"
                     js-depart="{{ $dernierTrajet->lieu_depart}}"
                     js-arrivee="{{ $dernierTrajet->lieu_arrivee}}"
                     {{-- Ajout de js-date pour que le JS puisse lire la date --}}
                     js-date="{{ $dernierTrajet->date_depart }}" 
                     js-heure="{{ \Carbon\Carbon::parse($dernierTrajet->heure_depart)->format('H:i') }}"
                     js-places="{{ $dernierTrajet->place_disponible}}"
                     js-vehicule="{{ $dernierTrajet->id_vehicule}}"
                     class="bg-white border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                    
                    <div class="text-sm">
                        <p class="font-medium flex items-start mb-1">
                            <span class="text-vert-principale mr-1 mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                            </span>
                            <span class="text-gris1 font-semibold mr-1">De:</span>
                            {{ $dernierTrajet->lieu_depart}}
                        </p>
                        
                        <p class="font-medium flex items-center mb-2 ml-5">
                            <span class="text-gris1 font-semibold mr-1">À:</span> 
                            {{ $dernierTrajet->lieu_arrivee}}
                        </p>

                        <div class="flex items-center text-xs text-gris1 mt-2">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="mr-4"> {{ \Carbon\Carbon::parse($dernierTrajet->heure_depart)->format('H:i') }} </span>
                            
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-5a3 3 0 00-3-3H9a3 3 0 00-3 3v5H1V7a3 3 0 013-3h16a3 3 0 013 3v13H17zM12 11a4 4 0 100-8 4 4 0 000 8z"></path></svg>
                            <span>{{ $dernierTrajet->place_disponible }}</span>
                        </div> 
                    </div>

                    <button type="button" class="bg-vert-principale text-white px-4 py-2 rounded-md font-medium hover:bg-vert-principal-h transition shadow-sm flex items-center shrink-0 cursor-pointer" id="btn-utiliser">
                        Utiliser
                    </button>
                </div>
            @else
                <div class="bg-white border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                    <p class="text-sm text-semibold">Vous n'avez pas encore de trajet enregistré</p>
                </div>
            @endisset
        </div>


        <div class="bg-white border border-gray-200 rounded-lg mb-8 p-6">
            <h2 class="text-lg font-semibold text-noir flex items-center">
                Détails du Trajet
            </h2>
            <p class="text-sm text-gris1 mb-6">Toutes les informations sont nécessaires pour publier votre trajet</p>

            <input type="hidden" id="coords_depart" name="coords_depart">
            <input type="hidden" id="coords_arrivee" name="coords_arrivee">

            <div class="space-y-6">
                
                {{--DÉPART--}}
                <div class="relative">
                    <label for="lieu_depart" class="block text-sm font-medium text-noir mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-vert-principale" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                        Lieu de Départ
                    </label>
                    <input type="text" name="lieu_depart" id="lieu_depart" autocomplete="off" value="{{ old('lieu_depart') }}" placeholder="Entrez votre adresse de départ" class="w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-1 focus:ring-vert-principale focus:border-vert-principale transition" required>
                    <ul id="liste-depart" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50 rounded-lg left-0"></ul>
                    <p id="error-lieu-depart" class="text-red-500 text-xs mt-1 hidden"></p> 
                    @error('lieu_depart')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

                {{--BOUTON INVERSER--}}
                <div class="flex justify-center -my-2 relative z-10">
                    <button type="button" id="btn-inverser-lieux" class="bg-gray-100 hover:bg-gray-200 text-gris1 p-2 rounded-full transition transform rotate-90 cursor-pointer shadow-sm border border-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </button>
                </div>

                {{--ARRIVÉE--}}
                <div class="relative">
                    <label for="lieu_arrivee" class="block text-sm font-medium text-noir mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-vert-principale" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                        Lieu d'Arrivée
                    </label>
                    <input type="text" name="lieu_arrivee" id="lieu_arrivee" autocomplete="off" value="{{ old('lieu_arrivee') }}" placeholder="Entrez votre adresse d'arrivée" class="w-full border rounded-md shadow-sm p-3 border-gray-300 focus:ring-1 focus:ring-vert-principale focus:border-vert-principale transition" required>
                    <ul id="liste-arrivee" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50 rounded-lg left-0"></ul>
                    <p id="error-lieu-arrivee" class="text-red-500 text-xs mt-1 hidden"></p>

                    @error('lieu_arrivee_min')
                        <p class="text-red-500 text-xs italic mt-1">Le lieu de départ et le lieu d'arrivée ne peuvent pas être identiques.</p>
                    @enderror
                </div>

            <div class="mt-6">
                {{--DATE ET HEURE--}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_depart" class="font-bold text-noir text-sm mb-2 block">Date</label>
                        <div class="relative">
                            <input type="text" name="date_depart" id="date_depart" value="{{ old('date_depart') }}" placeholder="Sélectionner une date" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:border-vert-principale focus:outline-none cursor-pointer" required>
                            <div class="absolute right-4 top-3.5 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                        @error('date_depart')
                            <p class="text-red-500 text-xs italic mt-1">La date doit être égale ou supérieure à aujourd'hui.</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_depart" class="font-bold text-noir text-sm mb-2 block">Heure de Départ</label>
                        <div class="relative">
                            <input type="text" name="heure_depart" id="heure_depart" value="{{ old('heure_depart') }}" placeholder="Saisissez une heure" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:border-vert-principale focus:outline-none cursor-pointer" required>
                            <div class="absolute right-4 top-3.5 pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{--NOMBRE DE PLACES--}}
                <div class="mt-6">
                    <label for="places_disponibles" class="block text-sm font-medium text-noir mb-1 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-vert-principale" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-5a3 3 0 00-3-3H9a3 3 0 00-3 3v5H1V7a3 3 0 013-3h16a3 3 0 013 3v13H17zM12 11a4 4 0 100-8 4 4 0 000 8z"></path></svg>
                        Nombre de Places Disponibles
                    </label>
                        <select name="places_disponibles" id="places_disponibles" class="w-full border border-gray-300 rounded-md shadow-sm p-3 " required>
                            <option value="" disabled {{ old('places_disponibles') ? '' : 'selected' }}>Sélectionnez le nombre de place</option>
                            <option value="1" {{ old('places_disponibles') == '1' ? 'selected' : '' }}>1 place</option>
                            <option value="2" {{ old('places_disponibles') == '2' ? 'selected' : '' }}>2 places</option>
                            <option value="3" {{ old('places_disponibles') == '3' ? 'selected' : '' }}>3 places</option>
                            <option value="4" {{ old('places_disponibles') == '4' ? 'selected' : '' }}>4 places</option>
                            <option value="5" {{ old('places_disponibles') == '5' ? 'selected' : '' }}>5 places</option>
                            <option value="6" {{ old('places_disponibles') == '6' ? 'selected' : '' }}>6 places</option>
                            <option value="7" {{ old('places_disponibles') == '7' ? 'selected' : '' }}>7 places</option>
                        </select>
                </div>

                {{--SELECTION VEHICULE--}}
                <div class="mb-6 mt-6">
                    <label for="vehicule_id" class="block text-sm font-medium text-noir mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-vert-principale" fill="none" stroke="currentColor" viewBox="0 0 64 28" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 18L9 12C10 10 12 8 15 8H32C34.5 8 36.5 9 38 10.5C40 12.5 42 14.5 46 14.5H52C53.7 14.5 55 15.8 55 17.5V19C55 19.6 54.6 20 54 20H51C51 22.8 48.8 25 46 25C43.2 25 41 22.8 41 20H23C23 22.8 20.8 25 18 25C15.2 25 13 22.8 13 20H8C7.4 20 7 19.6 7 19V18H6Z" stroke="currentColor" stroke-width="3" fill="none" stroke-linejoin="round" stroke-linecap="round"/>
                            <circle cx="18" cy="20" r="4" stroke="currentColor" stroke-width="3" fill="none"/>
                            <circle cx="46" cy="20" r="4" stroke="currentColor" stroke-width="3" fill="none"/>
                        </svg>
                        Véhicule utilisé
                    </label>

                    @if ($vehicules->isEmpty())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
                            <p class="text-red-600 text-sm font-medium mb-3">Vous n'avez pas de véhicule enregistré.</p>
                            <a href="{{ route('vehicule.create', ['source' => 'trajet']) }}" onclick="saveFormData()" class="text-white font-medium bg-red-500 hover:bg-red-600 rounded-lg px-5 py-2 transition-colors">
                                + Ajouter un véhicule
                            </a>
                            @error('id_vehicule')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div class="relative">
                            <select name="id_vehicule" id="vehicule_id" class="w-full bg-gray-50 border border-gray-200 appearance-none bg-none" required>
                                <option value="" disabled selected>Sélectionnez votre véhicule</option>
                                @foreach ($vehicules as $vehicule)
                                    <option value="{{ $vehicule->id }}" 
                                        {{ session('new_vehicule_id') == $vehicule->id ? 'selected' : (old('id_vehicule') == $vehicule->id ? 'selected' : '') }}>
                                        {{ $vehicule->marque }} {{ $vehicule->modele }} ({{ $vehicule->immatriculation }})
                                    </option>
                                @endforeach
                            </select>
                            
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>

                        <div class="mt-2 text-right">
                            <a href="{{ route('vehicule.create', ['source' => 'trajet']) }}" onclick="saveFormData()" class="text-xs text-vert-principale hover:text-vert-principal-h font-medium hover:underline transition-colors">
                                + Ajouter un autre véhicule
                            </a>
                        </div>
                        
                        @error('id_vehicule')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                {{-- RECAPITULATIF --}}
                <div class="bg-beige-second/50 border border-beige-second/50 rounded-lg p-4 mt-8">
                    <h3 class="font-semibold text-noir mb-2">Récapitulatif</h3>
                    <p class="text-sm text-gris1 mb-4">Vérifiez vos informations avant de publier votre trajet.</p>
                    <p class="text-sm text-gris1 mb-4">Vous pourrez modifier ou annuler votre trajet depuis la page "Mes Trajets".</p>
                </div>

                {{-- ACTIONS --}}
                <div class="flex justify-end gap-3 pt-6">
                    <button type="submit" class="bg-vert-principale text-white px-15 py-2 rounded-md font-medium hover:bg-vert-principal-h transition shadow-sm cursor-pointer">
                        Publier le Trajet
                    </button>
                    <a href="{{ url('/') }}" class="bg-white text-gris1 px-6 py-2 rounded-md font-medium hover:bg-gray-50 transition shadow-sm cursor-pointer">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
        <input type="hidden" name="duree_trajet" id="duree_trajet" value="">
    </form>
</main>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
@vite(['resources/js/proposer.js'])
@endsection