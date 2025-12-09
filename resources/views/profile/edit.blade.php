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
            
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('patch')

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    
                    <div class="flex items-center gap-6 mb-6">
                        
                        <div class="relative w-24 h-24">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-beige-principale flex items-center justify-center">
                                @if($user->Photo)
                                    <img id="preview_image" src="{{ asset('storage/' . $user->Photo) }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <img id="preview_image" src="{{ asset('images/accueil/icones/personne-convivialite-vert.png') }}" class="w-10 h-10 object-contain">
                                @endif
                            </div>

                            <label for="photo_input" 
                                   class="absolute bottom-0 right-0 bg-vert-principale text-white p-2 rounded-full cursor-pointer hover:bg-vert-principal-h transition shadow-md z-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </label>
                            
                            <input type="file" name="Photo" id="photo_input" class="hidden" 
                                   accept="image/*"
                                   onchange="loadPreview(event)">
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-noir">Photo de profil</h2>
                            <p class="text-sm text-gris1">Cliquez sur la caméra pour changer</p>
                        </div>
                    </div>
                    
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
                        </div>

                        <div>
                            <label for="phone" class="block text-sm text-gris1 mb-2">Téléphone</label>
                            <input type="text" name="phone" id="phone" 
                                value="{{ old('phone', $user->Numero ?? $user->phone) }}" 
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
                                <div><h4 class="font-semibold text-noir">Accepter les animaux</h4></div>
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
                                <div><h4 class="font-semibold text-noir">Fumeur</h4></div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="Accepte_fumeurs" value="0">
                                <input type="checkbox" name="Accepte_fumeurs" value="1" class="sr-only peer"
                                    {{ old("Accepte_fumeurs", $user->preference?->Accepte_fumeurs) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-vert-principale/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vert-principale"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('images/accueil/icones/musique.png') }}" class="size-6 object-contain">
                                <div><h4 class="font-semibold text-noir">Musique</h4></div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="Accepte_musique" value="0">
                                <input type="checkbox" name="Accepte_musique" value="1" class="sr-only peer"
                                    {{ old("Accepte_musique", $user->preference?->Accepte_musique) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-vert-principale/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vert-principale"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('profile.show') }}" class="text-gris1 hover:text-noir font-medium px-4 py-2">Annuler</a>
                    <button type="submit" class="bg-vert-principale hover:bg-vert-principal-h text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

        </div>
    </div>

<div class="max-w-4xl mx-auto mt-10 bg-white rounded-2xl shadow-sm border border-red-100 p-6 lg:p-8">
        
        <div class="flex items-start gap-4">
            <div class="p-3 bg-red-50 rounded-full shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <div class="flex-1" 
                 x-data="{ 
                    open: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}, 
                    password: '' 
                 }">
                 
                <h2 class="text-xl font-bold text-noir">Supprimer le compte</h2>
                <p class="text-sm text-gris1 mt-1 max-w-xl">
                    Une fois votre compte supprimé, toutes vos ressources et données seront définitivement effacées.
                </p>
                
                <button type="button" @click.prevent="open = true" class="mt-4 bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium transition-colors text-sm">
                    Je veux supprimer mon compte
                </button>

                <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        
                        <div x-show="open" 
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                             class="fixed inset-0 bg-beige-principale bg-opacity-90 transition-opacity" 
                             @click="open = false" 
                             aria-hidden="true"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div x-show="open" @click.stop
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-50">
                            
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                @csrf
                                @method('delete')

                                <h2 class="text-lg font-medium text-gray-900">
                                    Êtes-vous sûr de vouloir supprimer votre compte ?
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Veuillez entrer votre mot de passe pour confirmer. Cette action est irréversible.
                                </p>

                                <div class="mt-6">
                                    <label for="password" class="sr-only">Mot de passe</label>
                                    
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        x-model="password" 
                                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm"
                                        placeholder="Votre mot de passe actuel"
                                    />
                                    
                                    @error('password', 'userDeletion')
                                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" @click="open = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                        Annuler
                                    </button>

                                    <button type="submit" 
                                            :disabled="!password"
                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                        Supprimer définitivement
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
</div>

<script>
    function loadPreview(event) {
        var output = document.getElementById('preview_image');
        if (event.target.files && event.target.files[0]) {
            output.src = URL.createObjectURL(event.target.files[0]);
            output.classList.remove('w-10', 'h-10', 'object-contain');
            output.classList.add('w-full', 'h-full', 'object-cover');
            
            output.onload = function() {
                URL.revokeObjectURL(output.src) 
            }
        }
    };
</script>


@endsection