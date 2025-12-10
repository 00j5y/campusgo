@props(['trajet', 'mode'])

<div class="bg-white border border-[#2E7D32] rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-start">
        
        <div class="flex-grow space-y-3">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-location-dot text-[#2E7D32] mt-1"></i>
                <div>
                    <p class="font-bold text-[#333]">{{ $trajet->Lieu_Depart }}</p>
                    <p class="text-xs text-gray-400">→ vers {{ $trajet->Lieu_Arrivee }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($trajet->Date_)->format('d/m/Y') }}</span>
                <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($trajet->Heure_Depart)->format('H:i') }}</span>
                
                @if($mode === 'search')
                    <span class="text-[#2E7D32] font-bold ml-2">{{ $trajet->Prix }}€</span>
                @endif
            </div>

            @if($mode === 'perso')
                <div class="flex gap-2">
                    @if($trajet->ID_Utilisateur == 1) 
                        <span class="bg-[#2E7D32] text-white text-[10px] font-bold px-2 py-1 rounded">Conducteur</span>
                    @else
                        <span class="bg-[#F59E0B] text-white text-[10px] font-bold px-2 py-1 rounded">Passager</span>
                    @endif
                    <span class="text-gray-500 text-xs flex items-center gap-1">
                        <i class="fa-solid fa-user-group"></i> {{ $trajet->Place_Disponible }} places
                    </span>
                </div>
            @endif
        </div>

        <div class="mt-4 md:mt-0 flex flex-col gap-2 w-full md:w-[160px]">
            @if($mode === 'search')
                <button onclick="openReserverModal({{ $trajet->ID_Trajet }})" 
                        class="cursor-pointer bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-2 px-6 rounded-lg transition text-center shadow-sm">
                    Choisir
                </button>
            @else
                <button onclick="toggleTrajetMap('{{ $trajet->ID_Trajet }}', '{{ e($trajet->Lieu_Depart) }}', '{{ e($trajet->Lieu_Arrivee) }}')"
                        class="cursor-pointer w-full border border-[#2E7D32] text-[#2E7D32] hover:bg-[#2E7D32] hover:text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                    Voir la carte
                </button>
                <button onclick="openAnnulerModal({{ $trajet->ID_Trajet }})" 
                        class="cursor-pointer w-full bg-[#FF5A5F] hover:bg-[#E0484D] text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                    Annuler
                </button>
            @endif
        </div>
    </div>

    @if($mode === 'perso')
        <div id="map-container-{{ $trajet->ID_Trajet }}" class="hidden mt-6 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 mb-2">Itinéraire estimé :</p>
            <div id="map-{{ $trajet->ID_Trajet }}" class="h-64 w-full rounded-xl overflow-hidden border border-gray-200 bg-gray-50"></div>
        </div>
    @endif
</div>