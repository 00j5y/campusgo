@extends('layouts.app')

@section('title', 'Proposer un trajet - Campus\'GO')

@section('content')
<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 max-w-4xl">
    <header class="mb-10 mt-6 text-center">
        <h1 class="text-3xl font-semibold text-noir">Proposez votre covoiturage IUT</h1>
        <p class="mt-2 text-gris1">Remplissez les informations ci-dessous pour publier votre trajet</p>
    </header>

    <form method="POST" action="{{ route('trajets.store') }}">
    @csrf

    <!-- Réutiliser le trajet précédent -->
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

        <!-- Si l'utilisateur a déjà créé un trajet -->
        @isset($dernierTrajet)
            <div 
            id="dernier-trajet-data"
            js-depart="{{ $dernierTrajet->lieu_depart}}"
            js-arrivee="{{ $dernierTrajet->lieu_arrivee}}"
            js-heure="{{ \Carbon\Carbon::parse($dernierTrajet->heure_depart)->format('H:i') }}"
            js-places="{{ $dernierTrajet->place_disponible}}"
            js-vehicule="{{ $dernierTrajet->id_vehicule}}"
            class="bg-white border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                
                <div class="text-sm">
                    
                    <p class="font-medium flex items-start mb-1">
                        <span class="text-vert-principale mr-1 mt-1">
                            <!-- Icone Localisation -->
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
                        
                        <!-- Icone Horloge -->
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="mr-4"> {{ \Carbon\Carbon::parse($dernierTrajet->heure_depart)->format('H:i') }} </span>
                        
                        <!-- Places -->
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-5a3 3 0 00-3-3H9a3 3 0 00-3 3v5H1V7a3 3 0 013-3h16a3 3 0 013 3v13H17zM12 11a4 4 0 100-8 4 4 0 000 8z"></path></svg>
                        <span>{{ $dernierTrajet->place_disponible }}</span>
                    </div> 
                </div>
                    <button type="button" class="bg-vert-principale text-white px-4 py-2 rounded-md font-medium hover:bg-vert-principal-h transition shadow-sm flex items-center shrink-0 cursor-pointer"
                    id="btn-utiliser">
                    Utiliser
            </button>
            </div>
        
        @else
            <div class="bg-white border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                <p class="text-sm text-semibold">Vous n'avez pas encore de trajet enregistré</p>
            </div>
        @endisset
        
    </div>

    <!-- Détail du trajet -->
    <div class="bg-white border border-gray-200 rounded-lg mb-8 p-6">
            <h2 class="text-lg font-semibold text-noir flex items-center">
                Détails du Trajet
            </h2>
        <p class="text-sm text-gris1 mb-6">Toutes les informations sont nécessaires pour publier votre trajet</p>

        <!-- Lieu de Départ -->
        <div class="space-y-6">
            <div>
                <label for="lieu_depart" class="block text-sm font-medium text-noir mb-1 flex items-center">
                    <!-- Icone de localisation -->
                    <svg class="w-4 h-4 mr-1 text-vert-principale" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                    Lieu de Départ
                </label>
                <input type="text" name="lieu_depart" id="lieu_depart" placeholder="Entrez votre adresse de départ" 
                class="w-full border border-gray-300 rounded-md shadow-sm p-3 " required>
                <p class="text-xs text-gris1 mt-1">Exemple : 15 Rue des Étudiants, Amiens</p>
            </div>


            <!-- Lieu d'arrivée -->
            <div>
                <label for="lieu_arrivee" class="block text-sm font-medium text-noir mb-1 flex items-center">
                    <!-- Icone de localisation -->
                    <svg class="w-4 h-4 mr-1 text-vert-principale" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/></svg>
                    Lieu d'Arrivée
                </label>
                <input type="text" name="lieu_arrivee" id="lieu_arrivee" placeholder="Entrez votre adresse d'arrivée" class="w-full border rounded-md shadow-sm p-3 border-gray-300" required>
                @error('lieu_arrivee_min')
                <p class="text-red-500 text-xs italic mt-1">Le lieu de départ et le lieu d'arrivée ne peuvent pas être identiques.</p>
                @enderror
            </div>

            <!-- Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date_depart" class="block text-sm font-medium text-noir mb-1 flex items-center">
                        <!-- Icone Date -->
                        <svg class="w-4 h-4 mr-1 text-vert-principale" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Date
                    </label>
                    <input type="date" name="date_depart" id="date_depart" class="w-full border border-gray-300 rounded-md shadow-sm p-3 " required>
                    @error('date_depart')
                    <p class="text-red-500 text-xs italic mt-1">Le champ « Date » doit correspondre à une date suppérieur ou égale à celle d'aujourd'hui.</p>
                    @enderror
                </div>

                <!-- Heure de Départ -->
                <div>
                    <label for="heure_depart" class="block text-sm font-medium text-noir mb-1 flex items-center">
                        <!-- Icone Horloge -->
                        <svg class="w-4 h-4 mr-1 text-vert-principale" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Heure de Départ
                    </label>
                    <input type="time" name="heure_depart" id="heure_depart" class="w-full border border-gray-300 rounded-md shadow-sm p-3 " required>
                </div>
            </div>


            <!-- Nombre de Places Disponibles -->
            <div>
                <label for="places_disponibles" class="block text-sm font-medium text-noir mb-1 flex items-center">
                    <!-- Icone Personne -->
                    <svg class="w-4 h-4 mr-1 text-vert-principale" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-5a3 3 0 00-3-3H9a3 3 0 00-3 3v5H1V7a3 3 0 013-3h16a3 3 0 013 3v13H17zM12 11a4 4 0 100-8 4 4 0 000 8z"></path></svg>
                    Nombre de Places Disponibles
                </label>
                <select name="places_disponibles" id="places_disponibles" class="w-full border border-gray-300 rounded-md shadow-sm p-3 " required>
                    <option value="" disabled selected>Sélectionnez le nombre de place</option>
                    <option value="1">1 place</option>
                    <option value="2">2 places</option>
                    <option value="3">3 places</option>
                    <option value="4">4 places</option>
                    <option value="5">5 places</option>
                    <option value="6">6 places</option>
                    <option value="7">7 places</option>
                </select>
            </div>


            <!-- Véhicule -->
            <div>
                <div>
                <label for="voiture" class="block text-sm font-medium text-noir mb-1 flex items-center">
                    <!-- Icone voiture -->
                     <svg class="w-4 h-4 mr-1 text-vert-principale" fill="none" stroke="currentColor" viewBox="0 0 64 28" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 18L9 12C10 10 12 8 15 8H32C34.5 8 36.5 9 38 10.5C40 12.5 42 14.5 46 14.5H52C53.7 14.5 55 15.8 55 17.5V19C55 19.6 54.6 20 54 20H51C51 22.8 48.8 25 46 25C43.2 25 41 22.8 41 20H23C23 22.8 20.8 25 18 25C15.2 25 13 22.8 13 20H8C7.4 20 7 19.6 7 19V18H6Z"
                    stroke="currentColor" stroke-width="3" fill="none" stroke-linejoin="round" stroke-linecap="round"/>
                    <circle cx="18" cy="20" r="4" stroke="currentColor" stroke-width="3" fill="none"/>
                    <circle cx="46" cy="20" r="4" stroke="currentColor" stroke-width="3" fill="none"/>
                    </svg>
                    Véhicule
                </label>

                <!-- Si l'utilisateur n'a pas de véhicule d'enregistré -->
                @if ($vehicules->isEmpty())
                    <div class="bg-red-100 border border-red-400 rounded-lg block text-sm text-red-700 italic text-center p-4 space-y-3">
                        <p>Vous n'avez pas de véhicule enregistré</p>
                        <a href="#" class="text-white font-medium bg-rouge border-red400 rounded-lg px-5 py-2 hover:bg-red-700">
                        + Ajouter un véhicule
                        </a>
                        @error('id_vehicule')
                        <p class="text-red-500 text-xs italic mt-4">Vous devez ajouter un véhicule</p>
                        @enderror
                    </div>
                <!-- Sinon -->   
                @else
                    <select name="id_vehicule" id="vehicule_id" class="w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-vert-principale focus:border-vert-principale focus:outline-none" required>
                        <option value="" disabled selected>Sélectionnez votre véhicule</option>
                        
                        <!-- Afficher tous les véhicules enregistrés -->
                        @foreach ($vehicules as $vehicule)
                            <option value="{{ $vehicule->id }}">
                            {{ $vehicule->marque }} {{ $vehicule->modele }} ({{ $vehicule->immatriculation }})
                            </option>
                        @endforeach
                    </select>
        
                    <p class="text-xs text-gris1 mt-2 text-right">
                    <a href="#" class="text-vert-principale hover:underline font-medium">+ Ajouter un autre véhicule</a>
                @endif
            </div>
            

        <!-- Récapitulatif -->
        <div class="bg-beige-second/50 border border-beige-second/50 rounded-lg p-4 mt-8">
            <h3 class="font-semibold text-noir mb-2">Récapitulatif</h3>
            <p class="text-sm text-gris1 mb-4">Vérifiez vos informations avant de publier votre trajet.</p>
            <p class="text-sm text-gris1 mb-4">Vous pourrez modifier ou annuler votre trajet depuis la page "Mes Trajets".</p>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end gap-3 pt-6">
            <button type="submit" class="bg-vert-principale text-white px-15 py-2 rounded-md font-medium hover:bg-vert-principal-h transition shadow-sm cursor-pointer">
                Publier le Trajet
            </button>
            <a href="{{ url('/') }}" class="bg-white text-gris1 px-6 py-2 rounded-md font-medium hover:bg-gray-50 transition shadow-sm cursor-pointer">
                Annuler
            </a>
        </div>
    </div>
</main>
@endsection
@section('scripts')
@endsection