@props(['trajet', 'mode', 'etat' => null])

@php
    // Détection du trajet "vide" dans l'historique
    $isTrajetVide = ($mode === 'perso' && $etat === 'passe' && Auth::id() === $trajet->id_utilisateur && $trajet->passagers->count() === 0);

    $cardClasses = $isTrajetVide 
        ? 'bg-gray-100 border-gray-300 opacity-60 grayscale cursor-not-allowed' // Style Gris
        : 'bg-white border-[#2E7D32] hover:shadow-md cursor-pointer'; // Style Normal (Vert)
@endphp

<div class="{{ $cardClasses }} border rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-start">
        
        <div class="flex-grow space-y-3">
            {{-- ITINÉRAIRE --}}
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-location-dot text-[#2E7D32] mt-1"></i>
                <div>
                    <p class="font-bold text-[#333]">{{ $trajet->lieu_depart }}</p>
                    <p class="text-xs text-gray-400">→ vers {{ $trajet->lieu_arrivee }}</p>
                </div>
            </div>

           {{-- INFOS DATE / HEURE / PRIX --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mt-1">
                {{-- Date --}}
                <span><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }}</span>
                
                <div class="flex items-center gap-3">
                    
                    {{-- Heure : Départ ➝ Arrivée --}}
                    <span class="flex items-center bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                        <i class="far fa-clock mr-2 text-vert-principale"></i> 
                        
                        {{-- Heure Départ --}}
                        <span class="font-bold text-gray-700">
                            {{ \Carbon\Carbon::parse($trajet->heure_depart)->format('H:i') }}
                        </span>
                        
                        {{-- Heure Arrivée --}}
                        @if(!empty($trajet->heure_arrivee) && $trajet->heure_arrivee != '00:00:00')
                            <i class="fa-solid fa-arrow-right mx-2 text-gray-400 text-[10px]"></i>
                            <span class="text-gray-500 font-medium">
                                {{ \Carbon\Carbon::parse($trajet->heure_arrivee)->format('H:i') }}
                            </span>
                        @endif
                    </span>

                    {{-- Prix --}}
                    <span class="text-[#2E7D32] font-bold text-base">
                        @if($trajet->prix == 0)
                            Gratuit
                        @else
                            {{ number_format($trajet->prix, 0, ',', ' ') }} €
                        @endif
                    </span>
                </div>
            </div>

            {{-- INFO CONDUCTEUR (Visible pour les autres) --}}
            @if($trajet->conducteur && Auth::id() !== $trajet->id_utilisateur)
            <div class="mt-2 pt-2 border-t border-gray-100">
                <a href="{{ route('profile.public', $trajet->conducteur->id) }}" class="flex items-center gap-2 group hover:bg-gray-50 p-1 rounded-lg transition-colors cursor-pointer">
                    @if($trajet->conducteur->photo)
                        <img src="{{ asset('storage/' . $trajet->conducteur->photo) }}" 
                             alt="{{ $trajet->conducteur->prenom }}" 
                             class="w-8 h-8 rounded-full object-cover border border-gray-200 group-hover:border-vert-principale transition-colors">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600 group-hover:bg-gray-300 transition-colors">
                            {{ substr($trajet->conducteur->prenom, 0, 1) }}{{ substr($trajet->conducteur->nom, 0, 1) }}
                        </div>
                    @endif
                    
                    <div class="flex flex-col">
                        <p class="text-sm font-bold text-noir group-hover:text-vert-principale transition-colors">
                            {{ $trajet->conducteur->prenom }} {{ substr($trajet->conducteur->nom, 0, 1) }}.
                        </p>
                        <p class="text-[10px] text-gray-400 group-hover:text-vert-principale/70">
                            Voir le profil conducteur
                        </p>
                    </div>
                </a>
            </div>
            @endif

            {{-- BADGES --}}
            @if($mode === 'perso')
                <div class="flex gap-2 mt-2">
                    @if($isTrajetVide)
                        {{-- Badge Spécial Trajet Vide --}}
                        <span class="bg-gray-400 text-white text-[10px] font-bold px-2 py-1 rounded">
                            Aucun passager
                        </span>
                    @else
                        {{-- Badges Normaux --}}
                        @if(Auth::check() && $trajet->id_utilisateur == Auth::id()) 
                            <span class="bg-[#2E7D32] text-white text-[10px] font-bold px-2 py-1 rounded">Conducteur (Moi)</span>
                        @else
                            <span class="bg-[#F59E0B] text-white text-[10px] font-bold px-2 py-1 rounded">Passager</span>
                        @endif
                        
                        <span class="text-gray-500 text-xs flex items-center gap-1">
                            <i class="fa-solid fa-user-group"></i> {{ $trajet->place_disponible }} places
                        </span>
                    @endif
                </div>
            @endif

            {{-- LISTE DES PASSAGERS (Visible uniquement par le conducteur) --}}
            {{-- On vérifie si l'utilisateur est connecté + est le conducteur + Il y a des passagers --}}
            @if(Auth::check() && Auth::id() === $trajet->id_utilisateur && $trajet->passagers->count() > 0)
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide">
                        <i class="fa-solid fa-users text-[#2E7D32] mr-1"></i> Vos Passagers :
                    </p>
                    
                    <div class="flex flex-wrap gap-3">
                        {{-- On boucle sur 'passagers' défini dans le modèle Trajet --}}
                        @foreach($trajet->passagers as $passager)
                            <a href="{{ route('profile.public', $passager->id) }}" 
                               class="flex items-center gap-2 bg-gray-50 hover:bg-[#2E7D32]/10 border border-gray-200 hover:border-[#2E7D32] pr-3 pl-1 py-1 rounded-full transition-all group cursor-pointer">
                                
                                {{-- Avatar Passager --}}
                                @if($passager->photo)
                                    <img src="{{ asset('storage/' . $passager->photo) }}" 
                                         alt="{{ $passager->prenom }}" 
                                         class="w-6 h-6 rounded-full object-cover">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gray-200 group-hover:bg-[#2E7D32] group-hover:text-white flex items-center justify-center text-[9px] font-bold text-gray-600 transition-colors">
                                        {{ substr($passager->prenom, 0, 1) }}
                                    </div>
                                @endif

                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700 group-hover:text-[#2E7D32]">
                                        {{ $passager->prenom }} {{ substr($passager->nom, 0, 1) }}.
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- BOUTONS D'ACTION --}}
        <div class="mt-4 md:mt-0 flex flex-col gap-2 w-full md:w-[160px]">
            @if($mode === 'search')
                @if(Auth::id() !== $trajet->id_utilisateur)
                    <button onclick="openReserverModal('{{ route('reserver', $trajet->id) }}')" 
                            class="cursor-pointer bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-2 px-6 rounded-lg transition text-center shadow-sm">
                        Choisir
                    </button>
                @else
                    <span class="text-center text-xs text-gray-400 italic py-2">Votre trajet</span>
                @endif
            @else
                <button onclick="toggleTrajetMap('{{ $trajet->id }}', '{{ addslashes($trajet->lieu_depart) }}', '{{ addslashes($trajet->lieu_arrivee) }}')"
                        class="cursor-pointer w-full border border-[#2E7D32] text-[#2E7D32] hover:bg-[#2E7D32] hover:text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                    Voir la carte
                </button>
                
                @if($etat !== 'passe')
                    {{-- Si le trajet est À VENIR -> Bouton Annuler --}}
                    @if($trajet->id_utilisateur === Auth::id())
                        <button 
                            onclick="openModal('modal-annuler', '{{ route('trajets.destroy', $trajet->id) }}')" 
                            class="text-red-500 hover:text-red-700 font-bold border border-red-200 bg-red-50 px-3 py-1 rounded-lg">
                            Supprimer le trajet
                        </button>
                    @else
                        <button 
                            onclick="openModal('modal-annuler', '{{ route('annuler', $trajet->id) }}')" 
                            class="text-orange-500 hover:text-orange-700 font-bold border border-orange-200 bg-orange-50 px-3 py-1 rounded-lg">
                            Annuler ma réservation
                        </button>
                    @endif
                @else
                {{-- Si le trajet est PASSÉ --}}
                    
                    {{-- Je suis le conducteur (Auto-notation interdite) --}}
                    @if(Auth::id() === $trajet->id_utilisateur)

                        <button onclick="alert('Vous ne pouvez pas vous noter vous-même sur votre propre trajet.');" 
                                class="cursor-pointer w-full border border-gray-300 text-gray-400 font-bold py-2 px-4 rounded-lg transition text-sm text-center flex items-center justify-center gap-2 hover:bg-gray-50 opacity-70">
                            <i class="fa-regular fa-star"></i> Noter
                        </button>

                    {{-- J'ai déjà laissé un avis (Doublon interdit) --}}
                    @elseif($trajet->aDejaUnAvis())

                        <button onclick="alert('Oups ! Vous avez déjà donné votre avis sur ce trajet.');" 
                                class="cursor-pointer w-full border border-gray-300 text-gray-400 font-bold py-2 px-4 rounded-lg transition text-sm text-center flex items-center justify-center gap-2 hover:bg-gray-50">
                            <i class="fa-solid fa-check"></i> Déjà noté
                        </button>

                    {{-- Tout est OK, je peux noter --}}
                    @else

                        <a href="{{ route('reviews.create', ['id_trajet' => $trajet->id]) }}" 
                        class="cursor-pointer w-full border border-yellow-500 text-yellow-600 hover:bg-yellow-500 hover:text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center flex items-center justify-center gap-2">
                            <i class="fa-regular fa-star"></i> Noter
                        </a>

                    @endif

                @endif
            @endif
        </div>
    </div>

    {{-- CARTE DÉPLIABLE --}}
    @if($mode === 'perso')
        <div id="map-container-{{ $trajet->id }}" class="hidden mt-6 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 mb-2">Itinéraire estimé :</p>
            <div id="map-{{ $trajet->id }}" class="h-64 w-full rounded-xl overflow-hidden border border-gray-200 bg-gray-50"></div>
        </div>
    @endif
</div>