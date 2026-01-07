@extends('layouts.app')

@section('title', 'Modifier mon profil - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-10 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold text-noir">Modifier mon profil</h1>
                <p class="text-gris1 mt-2">Mettez à jour vos informations et vos préférences</p>
            </div>
            <a href="{{ route('profile.show') }}" class="text-vert-principale hover:underline font-medium">
                &larr; Retour au profil
            </a>
        </div>

        <div class="max-w-4xl mx-auto">
            
            <x-flash-message />
            
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('patch')

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    
                    <div class="flex items-center gap-6 mb-6">
                        
                        <div class="relative w-24 h-24 group">
    
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-200 flex items-center justify-center relative">
                                
                                {{-- 1. L'IMAGE --}}
                                <img id="img_photo_preview" 
                                    src="{{ $user->photo ? asset('storage/' . $user->photo) : '#' }}" 
                                    class="{{ $user->photo ? 'block' : 'hidden' }} w-full h-full object-cover absolute inset-0 z-10 bg-gray-200">

                                {{-- 2. LES INITIALES --}}
                                <span id="initials_placeholder" 
                                    class="{{ $user->photo ? 'hidden' : 'flex' }} w-full h-full items-center justify-center text-3xl font-bold text-gray-500 uppercase select-none z-0">
                                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                </span>

                                {{-- 3. BOUTON SUPPRESSION --}}
                                <div id="btn_photo_delete" 
                                    class="{{ !$user->photo ? 'hidden' : '' }} absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-20 cursor-pointer">
                                    <button type="button" class="text-white hover:text-red-400 transform hover:scale-110 transition-transform" title="Supprimer la photo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- LABEL CAMÉRA --}}
                            <label for="input_photo_upload" class="absolute bottom-0 right-0 bg-vert-principale text-white p-2 rounded-full cursor-pointer hover:bg-vert-principal-h transition shadow-md z-30 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </label>
                            
                            <input type="file" name="photo" id="input_photo_upload" class="hidden" accept="image/*">
                            <input type="hidden" name="delete_photo" id="input_delete_photo" value="0">
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-noir">Photo de profil</h2>
                            <p class="text-sm text-gris1">Cliquez sur la caméra pour changer.</p>
                            <p class="text-xs text-gris1 mt-1">Formats : JPG, PNG. Max 2Mo.</p>
                            @error('photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input-text name="firstname" label="Prénom" :value="$user->prenom" />
                        <x-input-text name="lastname" label="Nom" :value="$user->nom" />
                        <div class="md:col-span-2">
                            <x-input-text name="email" label="Email Universitaire" type="email" :value="$user->email" readonly />
                        </div>
                        <x-input-text name="num_tel" label="Téléphone" :value="$user->num_tel" placeholder="06 12 34 56 78" />
                    </div>
                </div>

                {{-- SECTION PRÉFÉRENCES --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                     <h2 class="text-xl font-bold text-noir mb-6 flex items-center gap-2">
                        <span class="w-10 h-10 rounded-full bg-beige-principale flex items-center justify-center">
                            <img src="{{ asset('images/profil/preference.png') }}" class="size-6">
                        </span>
                        Préférences de Covoiturage
                    </h2>
                    <div class="space-y-6">
                        <x-preference-row icon="patte" title="Accepter les animaux">
                            <input type="hidden" name="Accepte_animaux" value="0">
                            <x-toggle name="Accepte_animaux" :checked="old('Accepte_animaux', $user->preference?->accepte_animaux)" />
                        </x-preference-row>
                        <x-preference-row icon="cigarette" title="Fumeur">
                            <input type="hidden" name="Accepte_fumeurs" value="0">
                            <x-toggle name="Accepte_fumeurs" :checked="old('Accepte_fumeurs', $user->preference?->accepte_fumeurs)" />
                        </x-preference-row>
                        <x-preference-row icon="musique" title="Musique">
                            <input type="hidden" name="Accepte_musique" value="0">
                            <x-toggle name="Accepte_musique" :checked="old('Accepte_musique', $user->preference?->accepte_musique)" />
                        </x-preference-row>
                    </div>
                    <x-preference-slider name="accepte_discussion" :value="old('accepte_discussion', $user->preference?->accepte_discussion ?? 3)" />
                </div>

                <div class="flex items-center justify-end gap-4 mt-6">
                    <a href="{{ route('profile.show') }}" class="text-gris1 hover:text-noir font-medium px-4 py-2">Annuler</a>
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="form_photo_delete" action="{{ route('profile.photo.destroy') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script src="{{ asset('js/profile.js') }}?v={{ time() }}"></script>
@endsection