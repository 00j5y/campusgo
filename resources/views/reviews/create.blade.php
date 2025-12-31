@extends('layouts.app')

@section('title', 'Évaluer le trajet - Campus\'GO')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <a href="{{ route('historique-trajet') }}" class="text-vert-principale hover:underline font-medium flex items-center gap-1 mb-4">
                &larr; Retour à mes trajets
            </a>
            <h1 class="text-3xl font-bold text-noir">Évaluer le trajet</h1>
            <p class="text-gris1 mt-2">Votre avis aide la communauté Campus'Go à maintenir un service de qualité</p>
        </div>

        <div class="max-w-3xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- COLONNE GAUCHE : RÉCAPITULATIF TRAJET --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-beige-principale rounded-full flex items-center justify-center text-noir font-bold">
                            {{ substr($trajet->conducteur->prenom, 0, 1) }}{{ substr($trajet->conducteur->nom, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs text-gris1 uppercase">Conducteur</p>
                            <p class="font-bold text-noir text-lg">{{ $trajet->conducteur->prenom }} {{ $trajet->conducteur->nom }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 relative pl-4 border-l-2 border-gray-100">
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1.5 w-3 h-3 rounded-full bg-vert-principale"></div>
                            <p class="font-semibold text-noir">{{ $trajet->lieu_depart }}</p>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1.5 w-3 h-3 rounded-full bg-noir"></div>
                            <p class="font-semibold text-noir">{{ $trajet->lieu_arrivee }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2 text-gris1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ \Carbon\Carbon::parse($trajet->heure_depart)->format('H:i') }}
                        </div>
                        <div class="flex items-center gap-2 text-gris1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- COLONNE DROITE : FORMULAIRE --}}
            <div class="lg:col-span-2">
                
                <form action="{{ route('reviews.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8 space-y-8">
                    @csrf
                    
                    <input type="hidden" name="trajet_id" value="{{ $trajet->id }}">
                    
                    <div>
                        <h2 class="text-xl font-bold text-noir mb-2">Note globale <span class="text-red-500">*</span></h2>
                        <p class="text-sm text-gris1 mb-4">Notez votre expérience pour aider les autres membres</p>
                        
                        <div x-data="{ rating: 0, hover: 0 }" class="flex gap-2">
                            <input type="hidden" name="note" :value="rating" required>
                            
                            <template x-for="star in 5">
                                <button type="button" 
                                    @click="rating = star" 
                                    @mouseenter="hover = star" 
                                    @mouseleave="hover = 0"
                                    class="focus:outline-none transition-transform duration-150 hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" 
                                         :class="(hover || rating) >= star ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                                         viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                        @error('note')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="border-gray-100">

                    <div>
                        <label for="commentaire" class="block font-bold text-noir mb-2">Commentaire (optionnel)</label>
                        <textarea name="commentaire" id="commentaire" rows="4" 
                            class="w-full rounded-lg border-gray-300 focus:border-vert-principale focus:ring-vert-principale shadow-sm placeholder-gray-400"
                            placeholder="Partagez votre expérience de covoiturage..."></textarea>
                        <p class="text-xs text-gray-400 mt-2">Soyez constructif et respectueux dans vos commentaires.</p>
                    </div>

                    <div class="pt-4">
                        <label class="flex items-center gap-3 mb-6 cursor-pointer group">
                            <input type="checkbox" name="anonymous" class="rounded border-gray-300 text-vert-principale focus:ring-vert-principale w-5 h-5">
                            <span class="text-noir group-hover:text-vert-principale transition-colors">Publier cet avis de manière anonyme</span>
                        </label>

                        <button type="submit" class="w-full bg-vert-principale hover:bg-vert-principal-h text-white py-3 rounded-lg font-bold shadow-md transition-colors text-lg">
                            Publier l'avis
                        </button>

                        <div class="mt-4 text-center">
                            <a href="#" class="text-sm text-gray-400 hover:text-red-500 hover:underline">Signaler un problème</a>
                        </div>
                    </div>

                </form>

                <div class="mt-6 flex gap-3 text-xs text-gray-400 bg-blue-50 p-4 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>Vos avis sont essentiels pour maintenir la confiance et la qualité. Ils sont vérifiés et modérés pour garantir leur authenticité.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection