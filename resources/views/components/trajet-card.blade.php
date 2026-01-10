@props(['trajet', 'mode', 'etat' => null])

@php
    $isTrajetVide = ($mode === 'perso' && $etat === 'passe' && Auth::id() === $trajet->id_utilisateur && $trajet->passagers->count() === 0);
    $cardClasses = $isTrajetVide 
        ? 'bg-gray-100 border-gray-300 opacity-60 grayscale cursor-not-allowed' 
        : 'bg-white border-[#2E7D32] hover:shadow-md cursor-pointer';
@endphp

<div class="{{ $cardClasses }} border rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md">
    <div class="flex flex-col md:flex-row justify-between items-start">
        
        <div class="flex-grow space-y-3">
            {{-- 1. ITINÉRAIRE --}}
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-location-dot text-[#2E7D32] mt-1"></i>
                <div>
                    <p class="font-bold text-[#333]">{{ $trajet->lieu_depart }}</p>
                    <p class="text-xs text-gray-400">→ vers {{ $trajet->lieu_arrivee }}</p>
                </div>
            </div>

           {{-- 2. INFOS --}}
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mt-1">
                <span><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }}</span>
                <div class="flex items-center gap-3">
                    <span class="flex items-center bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                        <i class="far fa-clock mr-2 text-vert-principale"></i> 
                        <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($trajet->heure_depart)->format('H:i') }}</span>
                        @if(!empty($trajet->heure_arrivee) && $trajet->heure_arrivee != '00:00:00')
                            <i class="fa-solid fa-arrow-right mx-2 text-gray-400 text-[10px]"></i>
                            <span class="text-gray-500 font-medium">{{ \Carbon\Carbon::parse($trajet->heure_arrivee)->format('H:i') }}</span>
                        @endif
                    </span>
                    <span class="text-[#2E7D32] font-bold text-base">
                        {{ $trajet->prix == 0 ? 'Gratuit' : number_format($trajet->prix, 0, ',', ' ') . ' €' }}
                    </span>
                </div>
            </div>

            {{-- 3. CONDUCTEUR --}}
            @if($trajet->conducteur && Auth::id() !== $trajet->id_utilisateur)
                <div class="mt-2 pt-2 border-t border-gray-100 flex justify-between items-center">
                    
                    {{-- Profil Conducteur (A GAUCHE) --}}
                    <a href="{{ route('profile.public', $trajet->conducteur->id) }}" class="flex items-center gap-2 group hover:bg-gray-50 p-1 rounded-lg transition-colors cursor-pointer w-fit">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-600 overflow-hidden shrink-0">
                            @if($trajet->conducteur->photo)
                                <img src="{{ asset('storage/' . $trajet->conducteur->photo) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($trajet->conducteur->prenom, 0, 1) }}{{ substr($trajet->conducteur->nom, 0, 1) }}
                            @endif
                        </div>
                        <div class="flex flex-col items-start">
                            <p class="text-sm font-bold text-noir group-hover:text-vert-principale transition-colors leading-tight">
                                {{ $trajet->conducteur->prenom }} {{ substr($trajet->conducteur->nom, 0, 1) }}.
                            </p>
                            <span class="text-[10px] text-gray-400 group-hover:underline group-hover:text-[#2E7D32] transition-colors">
                                Voir le profil
                            </span>
                        </div>
                    </a>

                    {{-- Badge PASSAGER (A DROITE) --}}
                    @if($mode === 'perso')
                        <div class="flex items-center gap-2">
                             {{-- Petit label optionnel pour clarifier encore plus, sinon supprimer le span ci-dessous --}}
                             <span class="text-[10px] text-gray-400 hidden sm:inline">Votre statut :</span>
                             <span class="bg-[#F59E0B] text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Passager</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- 4. BADGES CONDUCTEUR & PLACES (S'affiche uniquement pour le conducteur) --}}
            @if($mode === 'perso' && Auth::id() === $trajet->id_utilisateur)
                <div class="flex flex-wrap items-center justify-start gap-2 mt-2 w-full pt-2 border-t border-gray-100">
                    <span class="bg-[#2E7D32] text-white text-[10px] font-bold px-2 py-1 rounded">
                        Conducteur (Moi)
                    </span>
                    <span class="text-gray-500 text-xs flex items-center gap-1 font-medium">
                        <i class="fa-solid fa-user-group text-gray-400"></i> {{ $trajet->place_disponible }} place(s) restante(s)
                    </span>
                </div>
            @endif

            {{-- 5. LISTE PASSAGERS (Si conducteur) --}}
            @if(Auth::check() && Auth::id() === $trajet->id_utilisateur && $trajet->passagers->count() > 0)
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wide"><i class="fa-solid fa-users text-[#2E7D32] mr-1"></i> Vos Passagers :</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($trajet->passagers as $passager)
                            <a href="{{ route('profile.public', $passager->id) }}" class="flex items-center gap-2 bg-gray-50 hover:bg-[#2E7D32]/10 border border-gray-200 hover:border-[#2E7D32] pr-3 pl-1 py-1 rounded-full transition-all group cursor-pointer">
                                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[9px] font-bold text-gray-600 overflow-hidden">
                                    @if($passager->photo) <img src="{{ asset('storage/' . $passager->photo) }}" class="w-full h-full object-cover"> @else {{ substr($passager->prenom, 0, 1) }} @endif
                                </div>
                                <span class="text-xs font-bold text-gray-700 group-hover:text-[#2E7D32]">{{ $passager->prenom }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- BOUTONS D'ACTION --}}
        <div class="mt-4 md:mt-0 flex flex-col gap-2 w-full md:w-[160px]">
            @if($mode === 'search')
                
                @auth
                    @if(Auth::id() !== $trajet->id_utilisateur)
                        <button onclick="openModal('modal-reserver', '{{ route('reserver', $trajet->id) }}')" 
                                class="cursor-pointer bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-2 px-6 rounded-lg transition text-center shadow-sm">
                            Choisir
                        </button>
                    @else
                        <span class="text-center text-xs text-gray-400 italic py-2">Votre trajet</span>
                    @endif
                @endauth

                @guest
                    <a href="{{ route('login', ['return_to' => url()->full()]) }}" 
                       class="cursor-pointer bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-600 font-bold py-2 px-4 rounded-lg transition text-sm text-center flex items-center justify-center gap-2">
                        <i class="fa-solid fa-lock text-xs"></i> Se connecter pour choisir
                    </a>
                @endguest

            @else
                {{-- MODE PERSO (HISTORIQUE) --}}
                <button onclick="toggleTrajetMap('{{ $trajet->id }}', '{{ addslashes($trajet->lieu_depart) }}', '{{ addslashes($trajet->lieu_arrivee) }}')"
                        class="cursor-pointer w-full border border-[#2E7D32] text-[#2E7D32] hover:bg-[#2E7D32] hover:text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                    Voir la carte
                </button>
                
                @if($etat !== 'passe')
                    @if($trajet->id_utilisateur === Auth::id())
                        <button onclick="openModal('modal-delete-trip', '{{ route('trajets.destroy', $trajet->id) }}')" 
                                class="text-red-500 hover:text-red-700 font-bold border border-red-200 bg-red-50 px-3 py-1 rounded-lg">
                            Supprimer
                        </button>
                    @else
                        <button onclick="openModal('modal-cancel-reservation', '{{ route('annuler', $trajet->id) }}')" 
                                class="text-orange-500 hover:text-orange-700 font-bold border border-orange-200 bg-orange-50 px-3 py-1 rounded-lg">
                            Annuler
                        </button>
                    @endif
                @else
                    @if(Auth::id() === $trajet->id_utilisateur)
                        @if($trajet->passagers->count() > 0)
                            <p class="text-xs text-gray-400 text-center mt-2 mb-1">Noter les passagers :</p>
                            @foreach($trajet->passagers as $passager)
                                <a href="{{ route('reviews.create', ['id_trajet' => $trajet->id, 'id_candidat' => $passager->id]) }}" class="cursor-pointer w-full border border-yellow-500 text-yellow-600 hover:bg-yellow-500 hover:text-white font-bold py-1 px-2 rounded-lg transition text-xs text-center flex items-center justify-center gap-1 mb-1">
                                    <i class="fa-regular fa-star"></i> {{ $passager->prenom }}
                                </a>
                            @endforeach
                        @else
                            <button disabled class="cursor-not-allowed w-full border border-gray-200 text-gray-400 font-bold py-2 px-4 rounded-lg text-xs text-center mt-2">Aucun passager</button>
                        @endif
                    @elseif($trajet->aDejaUnAvis())
                        <button onclick="alert('Déjà noté');" class="cursor-pointer w-full border border-gray-300 text-gray-400 font-bold py-2 px-4 rounded-lg flex items-center justify-center gap-2 hover:bg-gray-50"><i class="fa-solid fa-check"></i> Déjà noté</button>
                    @else
                        <a href="{{ route('reviews.create', ['id_trajet' => $trajet->id]) }}" class="cursor-pointer w-full border border-yellow-500 text-yellow-600 hover:bg-yellow-500 hover:text-white font-bold py-2 px-4 rounded-lg flex items-center justify-center gap-2"><i class="fa-regular fa-star"></i> Noter</a>
                    @endif
                @endif
            @endif
        </div>
    </div>

    @if($mode === 'perso')
        <div id="map-container-{{ $trajet->id }}" class="hidden mt-6 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-400 mb-2">Itinéraire estimé :</p>
            <div id="map-{{ $trajet->id }}" class="h-64 w-full rounded-xl overflow-hidden border border-gray-200 bg-gray-50"></div>
        </div>
    @endif
</div>
