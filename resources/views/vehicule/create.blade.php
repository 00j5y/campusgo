@extends('layouts.app')

@section('title', 'Ajouter un véhicule - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-noir">Ajouter un Véhicule</h1>
                <p class="text-gris1 mt-2">Renseignez les détails de votre voiture pour le covoiturage</p>
            </div>
            <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium">
                &larr; Retour au profil
            </a>
        </div>

        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
            
        <form method="post" action="{{ route('vehicule.store') }}" class="space-y-6"> @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Marque" class="block text-sm text-gris1 mb-2">Marque</label>
                        <input type="text" name="Marque" id="Marque" placeholder="Ex: Peugeot"
                            class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm">
                    </div>

                    <div>
                        <label for="Modele" class="block text-sm text-gris1 mb-2">Modèle</label>
                        <input type="text" name="Modele" id="Modele" placeholder="Ex: 208"
                            class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm">
                    </div>

                    <div>
                        <label for="Couleur" class="block text-sm text-gris1 mb-2">Couleur</label>
                        <input type="text" name="Couleur" id="Couleur" placeholder="Ex: Blanc"
                            class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm">
                    </div>

                    <div>
                        <label for="Immatriculation" class="block text-sm text-gris1 mb-2">Immatriculation</label>
                        <input type="text" name="Immatriculation" id="Immatriculation" placeholder="AB-123-CD"
                            class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm uppercase">
                    </div>

                    <div class="md:col-span-2">
                        <label for="NombrePlace" class="block text-sm text-gris1 mb-2">Nombre de places disponibles</label>
                        <input type="number" name="NombrePlace" id="NombrePlace" min="1" max="9" value="3"
                            class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm">
                        <p class="text-xs text-gray-400 mt-1">N'incluez pas la place du conducteur.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('profile.show') }}" class="text-gris1 hover:text-noir font-medium px-4 py-2">
                        Annuler
                    </a>
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">
                        Enregistrer le véhicule
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection