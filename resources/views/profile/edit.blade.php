@extends('layouts.app')

@section('title', 'Modifier mon profil - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-noir">Modifier mon Profil</h1>
                <p class="text-gris1 mt-2">Mettez à jour vos informations et vos préférences</p>
            </div>
            <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium">
                &larr; Retour au profil
            </a>
        </div>

        <div class="max-w-4xl mx-auto">
            
            <form method="post" action="{{ route('profile.update') }}" class="space-y-8">
                @csrf
                @method('patch')

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6 flex items-center gap-2">
                        <span class="w-10 h-10 rounded-full bg-beige-principale flex items-center justify-center">
                            <img src="{{ asset('images/accueil/icones/personne-convivialite-vert.png') }}" class="size-6">
                        </span>
                        Informations Personnelles
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="firstname" class="block text-sm text-gris1 mb-2">Prénom</label>
                            <input type="text" name="firstname" id="firstname" 
                                value="{{ old('firstname', $user->firstname) }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm">
                            @error('firstname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="lastname" class="block text-sm text-gris1 mb-2">Nom</label>
                            <input type="text" name="lastname" id="lastname" 
                                value="{{ old('lastname', $user->lastname) }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm">
                            @error('lastname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm text-gris1 mb-2">Email Universitaire</label>
                            <input type="email" name="email" id="email" 
                                value="{{ old('email', $user->email) }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed" readonly>
                            <p class="text-xs text-gray-400 mt-1">L'email universitaire ne peut pas être modifié.</p>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm text-gris1 mb-2">Téléphone</label>
                            <input type="text" name="phone" id="phone" 
                                value="{{ old('phone', $user->phone ?? $user->Numero) }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm"
                                placeholder="06 12 34 56 78">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6 flex items-center gap-2">
                         <span class="w-10 h-10 rounded-full bg-beige-principale flex items-center justify-center">
                            <img src="{{ asset('images/accueil/icones/feuille.png') }}" class="size-6">
                        </span>
                        Préférences de Covoiturage
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('images/accueil/icones/patte.png') }}" class="size-6 object-contain">
                                <div>
                                    <h4 class="font-semibold text-noir">Accepter les animaux</h4>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="Accepte_animaux" value="0">
                                <input type="checkbox" name="Accepte_animaux" value="1" class="sr-only peer" 
                                    {{ old('Accepte_animaux', $user->preference?->Accepte_animaux) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-vert-principale/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vert-principale"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('images/accueil/icones/cigarette.png') }}" class="size-6 object-contain">
                                <div>
                                    <h4 class="font-semibold text-noir">Fumeur</h4>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="Accepte_fumeurs" value="0">
                                <input type="checkbox" name="Accepte_fumeurs" value="1" class="sr-only peer"
                                    {{ old('Accepte_fumeurs', $user->preference?->Accepte_fumeurs) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-vert-principale/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vert-principale"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('images/accueil/icones/musique.png') }}" class="size-6 object-contain">
                                <div>
                                    <h4 class="font-semibold text-noir">Musique</h4>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="Accepte_musique" value="0">
                                <input type="checkbox" name="Accepte_musique" value="1" class="sr-only peer"
                                    {{ old('Accepte_musique', $user->preference?->Accepte_musique) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-vert-principale/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vert-principale"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('profile.show') }}" class="text-gris1 hover:text-noir font-medium px-4 py-2">
                        Annuler
                    </a>
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection