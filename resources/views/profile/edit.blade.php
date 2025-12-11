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

            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oups !</strong>
                    <span class="block sm:inline">Il y a des problèmes avec votre saisie :</span>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('patch')

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    
                    <div class="flex items-center gap-6 mb-6">
                        <div class="relative w-24 h-24">
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-beige-principale flex items-center justify-center">
                                @if($user->photo)
                                    <img id="preview_image" src="{{ asset('storage/' . $user->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    <img id="preview_image" src="{{ asset('images/accueil/icones/personne-convivialite-vert.png') }}" class="w-10 h-10 object-contain">
                                @endif
                            </div>

                            <label for="photo_input" 
                                   class="absolute bottom-0 right-0 bg-vert-principale text-white p-2 rounded-full cursor-pointer hover:bg-vert-principal-h transition shadow-md z-50 flex items-center justify-center"
                                   title="Changer la photo">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </label>
                            
                            <input type="file" name="photo" id="photo_input" class="hidden" accept="image/*" onchange="loadPreview(event)">
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-noir">Photo de profil</h2>
                            <p class="text-sm text-gris1">Cliquez sur la caméra pour changer.</p>
                            <p class="text-xs text-gris1 mt-1">Formats : JPG, PNG. Max 2Mo.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="firstname" class="block text-sm text-gris1 mb-2">Prénom</label>
                            <input type="text" name="firstname" id="firstname" 
                                   value="{{ old('firstname', $user->prenom) }}" 
                                   class="w-full rounded-md px-2 border border-beige-second focus:outline-none focus:border-beige-principale focus:ring-2 focus:ring-beige-principale shadow-sm">
                            @error('firstname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="lastname" class="block text-sm text-gris1 mb-2">Nom</label>
                            <input type="text" name="lastname" id="lastname" 
                                   value="{{ old('lastname', $user->nom) }}" 
                                   class="w-full rounded-md px-2 border border-beige-second focus:outline-none focus:border-beige-principale focus:ring-2 focus:ring-beige-principale shadow-sm">
                            @error('lastname') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm text-gris1 mb-2">Email Universitaire</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   class="w-full rounded-md px-2 border border-beige-second focus:outline-none focus:border-beige-principale focus:ring-2 focus:ring-beige-principale shadow-sm bg-gray-50 text-gray-500 cursor-not-allowed" readonly>
                        </div>

                        <div>
                            <label for="num_tel" class="block text-sm text-gris1 mb-2">Téléphone</label>
                            <input type="text" name="num_tel" id="num_tel" 
                                   value="{{ old('num_tel', $user->num_tel) }}" 
                                   class="w-full rounded-md px-2 border border-beige-second focus:outline-none focus:border-beige-principale focus:ring-2 focus:ring-beige-principale shadow-sm"
                                   placeholder="06 12 34 56 78">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-noir mb-6 flex items-center gap-2">
                         <span class="w-10 h-10 rounded-full bg-beige-principale flex items-center justify-center">
                            <img src="{{ asset('images/accueil/icones/preference.png') }}" class="size-6">
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
                                       {{ old('Accepte_animaux', $user->preference?->accepte_animaux) ? 'checked' : '' }}>
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
                                       {{ old("Accepte_fumeurs", $user->preference?->accepte_fumeurs) ? 'checked' : '' }}>
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
                                       {{ old("Accepte_musique", $user->preference?->accepte_musique) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-vert-principale/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vert-principale"></div>
                            </label>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border border-gray-100 bg-gray-50/50 mt-4">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="mt-1 shrink-0 text-vert-principale">
                                <img src="{{ asset('images/accueil/icones/discussion.png') }}" alt="Discussion" class="size-6 object-contain">
                            </div>
                            <div>
                                <h4 class="font-semibold text-noir">Envie de discuter ?</h4>
                                <p class="text-sm text-gris1">Quel genre de personne es-tu ?</p>
                            </div>
                        </div>

                        <div class="relative w-full px-2">
                            <div class="flex justify-between text-xs font-medium text-gris1 mb-2">
                                <span>Très Timide</span>
                                <span>Timide</span>
                                <span>Ni l'un ni l'autre</span>
                                <span>Bavard</span>
                                <span>Très Bavard</span>
                            </div>

                            <input type="range" 
                                   name="accepte_discussion" 
                                   min="1" max="5" step="1" 
                                   value="{{ old('accepte_discussion', $user->preference?->accepte_discussion ?? 3) }}" 
                                   class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                   style="accent-color: #68A35E;"> 
                            
                            <div class="flex justify-between w-full px-1 mt-1">
                                @for ($i = 0; $i < 5; $i++)
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                @endfor
                            </div>
                        </div>
                    </div>
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