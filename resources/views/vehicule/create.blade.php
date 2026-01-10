@extends('layouts.app')

@section('title', 'Ajouter un véhicule - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-noir">Ajouter un véhicule</h1>
                <p class="text-gris1 mt-2">Renseignez les détails de votre voiture pour le covoiturage</p>
            </div>
            <div class="mb-6">
                @if(request('source') == 'trajet')
                    <a href="{{ route('trajets.create') }}" class="text-vert-principale hover:underline font-medium flex items-center gap-2">
                        &larr; Retour à la proposition de trajet
                    </a>
                @else
                    <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium flex items-center gap-2">
                        &larr; Retour au profil
                    </a>
                @endif
            </div>
        </div>

        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
            <form method="post" action="{{ route('vehicule.store') }}" class="space-y-6"> 
                @csrf

                <input type="hidden" name="source" value="{{ request('source') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input-text name="Marque" label="Marque" placeholder="Ex: Bugatti" />

                <x-input-text name="Modele" label="Modèle" placeholder="Ex: Chiron" />

                <x-input-text name="Couleur" label="Couleur" placeholder="Ex: Rose fuchsia" />

                <div>
                    <x-input-text name="immatriculation" label="Immatriculation" placeholder="AB-123-CD" />
                    <p class="mt-1 text-xs text-gray-500">Format attendu : AA-123-AA</p>
                </div>

                <div class="md:col-span-2">
                    <x-input-text 
                        name="NombrePlace" 
                        label="Nombre de places disponibles" 
                        type="number" 
                        value="3" 
                        min="1" max="9" 
                    />
                    <p class="text-xs text-gray-400 mt-1">N'incluez pas la place du conducteur.</p>
                </div>
            </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                    @if(request('source') == 'trajet')
                    <a href="{{ route('trajets.create') }}" class="text-gris1 hover:text-noir font-medium px-4 py-2">
                        Annuler
                    </a>
                    @else
                        <a href="{{ route('profile.show') }}" class="text-gris1 hover:text-noir font-medium px-4 py-2">
                        Annuler
                    </a>
                    @endif
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">
                        Enregistrer le véhicule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection