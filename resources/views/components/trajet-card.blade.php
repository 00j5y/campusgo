@props(['trajet', 'mode', 'etat' => 'avenir']) 
{{-- On ajoute 'etat' qui vaut 'avenir' par défaut --}}

<div class="bg-white border border-[#2E7D32] rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-start">
        
        {{-- PARTIE GAUCHE (Infos) - INCHANGÉE --}}
        <div class="flex-grow space-y-3">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-location-dot text-[#2E7D32] mt-1"></i>
                <div>
                    <p class="font-bold text-[#333]">{{ $trajet->lieu_depart }}</p>
                    <p class="text-xs text-gray-400">→ vers {{ $trajet->lieu_arrivee }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }}</span>
                <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($trajet->heure_depart)->format('H:i') }}</span>

                {{-- Affichage du Conducteur --}}
                <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                        {{-- Initiale du prénom --}}
                        {{ substr($trajet->conducteur->prenom ?? 'U', 0, 1) }}
                    </div>
                <span>
                    Conduit par <span class="font-semibold text-[#333]">{{ $trajet->conducteur->prenom ?? 'Utilisateur' }} {{ $trajet->conducteur->nom ?? '' }}</span>
                </span>
            </div>
                
                @if($mode === 'search')
                    <span class="text-[#2E7D32] font-bold ml-2">{{ number_format($trajet->prix, 2) }}€</span>
                @endif
            </div>

            @if($mode === 'perso')
                <div class="flex gap-2">
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

        {{-- PARTIE DROITE (Boutons) - C'EST ICI QUE ÇA CHANGE --}}
        <div class="mt-4 md:mt-0 flex flex-col gap-2 w-full md:w-[160px]">
            
            {{-- CAS 1 : MODE RECHERCHE --}}
            @if($mode === 'search')
                <button onclick="openReserverModal('{{ route('reserver', $trajet->id) }}')" 
                        class="cursor-pointer bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-2 px-6 rounded-lg transition text-center shadow-sm">
                    Choisir
                </button>
            
            {{-- CAS 2 : MODE PERSO (Mes Trajets) --}}
            @else
                
                {{-- SOUS-CAS : TRAJET À VENIR --}}
                @if($etat === 'avenir')
                    <button onclick="toggleTrajetMap('{{ $trajet->id_trajet }}', '{{ addslashes($trajet->lieu_depart) }}', '{{ addslashes($trajet->lieu_arrivee) }}')"
                            class="cursor-pointer w-full border border-[#2E7D32] text-[#2E7D32] hover:bg-[#2E7D32] hover:text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                        Voir la carte
                    </button>
                    
                    <button onclick="openAnnulerModal({{ $trajet->id_trajet }})" 
                            class="cursor-pointer w-full bg-[#FF5A5F] hover:bg-[#E0484D] text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                        Annuler
                    </button>
                
                {{-- SOUS-CAS : TRAJET PASSÉ --}}
                @elseif($etat === 'passe')
                    {{-- Lien vers la page d'avis --}}
                    <a href="#" 
                       class="cursor-pointer w-full bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center block">
                        Laisser un avis
                    </a>
                @endif

            @endif
        </div>
    </div>

    {{-- ZONE CARTE (Uniquement si mode perso et à venir) --}}
    @if($mode === 'perso' && $etat === 'avenir')
        <div id="map-container-{{ $trajet->id_trajet }}" class="hidden mt-6 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 mb-2">Itinéraire estimé :</p>
            <div id="map-{{ $trajet->id }}" class="h-64 w-full rounded-xl overflow-hidden border border-gray-200 bg-gray-50"></div>
        </div>
    @endif
</div>