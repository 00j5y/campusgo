@props(['trajet', 'mode', 'etat' => null])

<div class="bg-white border border-[#2E7D32] rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
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
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }}</span>
                <span class="flex items-center whitespace-nowrap">
                    <i class="far fa-clock mr-1"></i> 
                    {{ \Carbon\Carbon::parse($trajet->heure_depart)->format('H:i') }}
    
                    {{-- Affiche l'arrivée seulement si elle existe --}}
                    @if(!empty($trajet->heure_arrivee) && $trajet->heure_arrivee != '00:00:00')
                        <span class="mx-2 text-gray-400 text-xs"><i class="fa-solid fa-arrow-right"></i></span>
                        {{ \Carbon\Carbon::parse($trajet->heure_arrivee)->format('H:i') }}
                    @endif
                </span>
                
                @if($mode === 'search')
                    {{-- Si le prix est 0, on écrit "Gratuit", sinon on affiche le montant --}}
                    <span class="text-[#2E7D32] font-bold ml-auto">
                        @if($trajet->prix == 0)
                            Gratuit
                        @else
                            {{ number_format($trajet->prix, 2, ',', ' ') }} €
                        @endif
                    </span>
                @endif
            </div>

            {{-- INFO CONDUCTEUR --}}
            @if($trajet->conducteur)
            <div class="flex items-center gap-2 mt-2 pt-2 border-t border-gray-100">
                
                {{-- LOGIQUE AVATAR --}}
                @if($trajet->conducteur->photo)
                    {{-- Si Photo --}}
                    <img src="{{ asset('storage/' . $trajet->conducteur->photo) }}" 
                         alt="{{ $trajet->conducteur->prenom }}" 
                         class="w-6 h-6 rounded-full object-cover border border-gray-200">
                @else
                    {{-- Si Pas de Photo --}}
                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600">
                        {{ substr($trajet->conducteur->prenom, 0, 1) }}{{ substr($trajet->conducteur->nom, 0, 1) }}
                    </div>
                @endif
                
                {{-- Nom --}}
                <p class="text-xs text-gray-500">
                    Proposé par <span class="font-bold text-noir">{{ $trajet->conducteur->prenom }} {{ $trajet->conducteur->nom }}</span>
                </p>
            </div>
            @endif

            {{-- BADGES --}}
            @if($mode === 'perso')
                <div class="flex gap-2 mt-2">
                    @if(Auth::check() && $trajet->id_utilisateur == Auth::id()) 
                        <span class="bg-[#2E7D32] text-white text-[10px] font-bold px-2 py-1 rounded">Conducteur (Moi)</span>
                    @else
                        <span class="bg-[#F59E0B] text-white text-[10px] font-bold px-2 py-1 rounded">Passager</span>
                    @endif
                    
                    <span class="text-gray-500 text-xs flex items-center gap-1">
                        <i class="fa-solid fa-user-group"></i> {{ $trajet->place_disponible }} places
                    </span>
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
                    <button onclick="openAnnulerModal('{{ route('annuler', $trajet->id) }}')" 
                            class="cursor-pointer w-full bg-[#FF5A5F] hover:bg-[#E0484D] text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                        Annuler
                    </button>
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