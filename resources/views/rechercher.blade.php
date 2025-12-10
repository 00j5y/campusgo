@extends('layouts.app')

@section('title', 'Rechercher un trajet - Campus\'GO')

@section('content')
    {{-- CSS Libraries --}}
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <main class="bg-[#F3EDE3] min-h-screen py-12 relative">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-semibold text-[#333] mb-2">Salut {{ $prenom }} ! Où voulez vous partir aujourd'hui ?</h1>
                <p class="text-gray-500">Trouvez un covoiturage en quelques clics.</p>
            </div>

            {{-- Notifications --}}
            @if(session('success'))
                <div class="max-w-4xl mx-auto mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="max-w-4xl mx-auto mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
            @endif

            {{-- Formulaire de Recherche --}}
            <div class="bg-white rounded-3xl shadow-xl p-8 max-w-4xl mx-auto mb-12 relative z-10">
                <form action="{{ route('rechercher') }}" method="GET" autocomplete="off" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" id="coords_depart" name="coords_depart" value="{{ request('coords_depart') }}">
                    <input type="hidden" id="coords_arrivee" name="coords_arrivee" value="{{ request('coords_arrivee') }}">
                    
                    {{-- Départ --}}
                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="font-bold text-gray-700 text-sm mb-2 block">Départ</label>
                        <i class="fa-solid fa-location-dot absolute left-4 top-[2.8rem] text-gray-400"></i>
                        <input type="text" id="depart" name="depart" value="{{ request('depart') }}" placeholder="Ville de départ..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-[#2E7D32] focus:outline-none focus:ring-1 focus:ring-[#2E7D32]">
                        <ul id="liste-depart" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50 rounded-lg"></ul>
                    </div>

                    {{-- Arrivée --}}
                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="font-bold text-gray-700 text-sm mb-2 block">Arrivée</label>
                        <i class="fa-solid fa-location-crosshairs absolute left-4 top-[2.8rem] text-[#2E7D32]"></i>
                        <input type="text" id="arrivee" name="arrivee" value="{{ request('arrivee') }}" placeholder="Ville d'arrivée..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-[#2E7D32] focus:outline-none focus:ring-1 focus:ring-[#2E7D32]">
                        <ul id="liste-arrivee" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50 rounded-lg"></ul>
                    </div>

                    {{-- Date & Heure --}}
                    <div>
                        <label class="font-bold text-gray-700 text-sm mb-2 block">Date</label>
                        <div class="relative">
                            <input type="text" id="date" name="date" value="{{ request('date') }}" placeholder="Sélectionner une date" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:border-[#2E7D32] focus:outline-none cursor-pointer">
                            <i class="fa-regular fa-calendar absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="font-bold text-gray-700 text-sm mb-2 block">Heure (Optionnel)</label>
                        <div class="relative">
                            <input type="text" id="heure" name="heure" value="{{ request('heure') }}" placeholder="08:00" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:border-[#2E7D32] focus:outline-none cursor-pointer">
                            <i class="fa-regular fa-clock absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Map Container --}}
                    <div class="col-span-1 md:col-span-2 mt-4">
                         <div id="map" class="w-full h-64 rounded-xl border border-gray-200 z-0"></div>
                    </div>

                    {{-- Bouton Submit --}}
                    <div class="col-span-1 md:col-span-2">
                        <button type="submit" class="cursor-pointer w-full bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-3 rounded-xl transition shadow-md flex justify-center items-center gap-2 transform active:scale-95 relative z-20">
                            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>

            {{-- Résultats de recherche --}}
            @if($rechercheFaite)
                <div class="max-w-4xl mx-auto mb-16 animate-fade-in-up">
                    <h2 class="text-xl font-bold text-[#333] mb-6 flex items-center gap-2">
                        <span>Trajets disponibles</span>
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">{{ $resultats->count() }}</span>
                    </h2>
                    
                    @if($resultats->isEmpty())
                        <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                            <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-magnifying-glass text-3xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-bold text-[#333]">Aucun trajet trouvé</h3>
                            <p class="text-gray-500 mt-2 max-w-md mx-auto">Nous n'avons pas trouvé de trajet correspondant à vos critères. Essayez de modifier vos paramètres de recherche ou proposez votre propre trajet !</p>
                            <a href="{{ route('trajets.create') }}" class="mt-6 inline-block bg-[#2E7D32] text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-[#1b5e20] transition">Proposer un trajet</a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($resultats as $trajet)
                                @include('components.trajet-card', ['trajet' => $trajet, 'mode' => 'search'])
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- Mes Prochains Trajets --}}
            <div class="max-w-4xl mx-auto">
                <div class="mb-6 flex justify-between items-end border-b pb-2">
                    <h2 class="text-xl font-bold text-[#333]">Vos Prochains Trajets</h2>
                    @if($mesTrajets->count() > 1)
                        <button id="btn-voir-tout" onclick="toggleVoirTout()" class="cursor-pointer text-sm text-[#2E7D32] font-semibold hover:underline bg-transparent border-0">
                                Voir tout ({{ $mesTrajets->count() }})
                        </button>
                    @endif
                </div>

                @if($mesTrajets->isEmpty())
                    <div class="text-center py-8 bg-white rounded-2xl border border-gray-100 text-gray-500 shadow-sm">
                        <p>Aucun trajet prévu pour le moment.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($mesTrajets as $trajet)
                            <div class="transition-all duration-300 {{ $loop->iteration > 1 ? 'hidden trajet-cache' : '' }}">
                                @include('components.trajet-card', ['trajet' => $trajet, 'mode' => 'perso'])
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            {{-- Footer CTA --}}
            <div class="bg-[#E0E5D5] rounded-3xl p-8 mt-16 text-center">
                <h3 class="font-bold text-[#333] text-lg mb-2">Prêt à partir ?</h3>
                <div class="flex justify-center gap-4 mt-4">
                    <a href="{{ route('trajets.create') }}" class="bg-[#2E7D32] text-white px-6 py-2.5 rounded-lg font-bold text-sm hover:bg-[#1b5e20] transition">Proposer</a>
                    <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" class="cursor-pointer bg-white text-[#333] px-6 py-2.5 rounded-lg font-bold text-sm border hover:bg-gray-50 transition">Rechercher</button>
                </div>
                <h4 class="text-gray-500 text-sm mt-4">Rejoignez la communauté Campus'Go et rendez vos trajets plus agréables.</h4>
            </div>

        </div>
    </main>

    {{-- MODALE RESERVER --}}
    <div id="modal-reserver" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title-reserver" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-reserver')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center transform transition-all scale-100">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <i class="fa-solid fa-check text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Confirmer la réservation</h3>
                <p class="text-gray-500 text-sm mb-6">Voulez-vous réserver ce trajet ?</p>
                
                <form id="form-reserver" action="" method="POST" class="flex gap-3 justify-center w-full">
                    @csrf 
                    <button type="button" onclick="closeModal('modal-reserver')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-bold transition">Retour</button>
                    <button type="submit" class="flex-1 bg-[#2E7D32] hover:bg-[#1b5e20] text-white px-4 py-3 rounded-xl font-bold transition shadow-lg">Réserver</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODALE ANNULER --}}
    <div id="modal-annuler" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title-annuler" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-annuler')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full text-center transform transition-all scale-100">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Annuler le trajet ?</h3>
                <p class="text-gray-500 text-sm mb-6">Êtes-vous sûr de vouloir continuer cette action ?</p>
                
                <form id="form-annuler" action="" method="POST" class="flex gap-3 justify-center w-full">
                    @csrf 
                    <button type="button" onclick="closeModal('modal-annuler')" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-3 rounded-xl font-bold transition">Retour</button>
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-xl font-bold transition shadow-lg">Confirmer</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
    <script>
        // CONFIG
        mapboxgl.accessToken = 'pk.eyJ1IjoiZ2FieXNjb3RlIiwiYSI6ImNtaXlueXBycDBlMnIzZnM3NDF0aWZ4emIifQ.Kv51hN4zyQ9O2AZLlbSdZg'; 

        // 1. UI FUNCTIONS
        function closeModal(id) { 
            document.getElementById(id).classList.add('hidden'); 
        }
        
        function openReserverModal(id) { 
            const form = document.getElementById('form-reserver');
            form.action = "/trajet/reserver/" + id; 
            document.getElementById('modal-reserver').classList.remove('hidden'); 
        }
        
        function openAnnulerModal(id) { 
            const form = document.getElementById('form-annuler');
            form.action = "/trajet/annuler/" + id; 
            document.getElementById('modal-annuler').classList.remove('hidden'); 
        }
        
        function toggleVoirTout() {
            const btn = document.getElementById('btn-voir-tout');
            const hiddenElements = document.querySelectorAll('.trajet-cache');
            let isShowing = false;

            hiddenElements.forEach(el => {
                if(el.classList.contains('hidden')) { 
                    el.classList.remove('hidden'); 
                    isShowing = true; 
                } else { 
                    el.classList.add('hidden'); 
                    isShowing = false; 
                }
            });
            btn.innerText = isShowing ? "Voir moins" : "Voir tout (" + (hiddenElements.length + 1) + ")";
        }

        // 2. MAPS INDIVIDUELLES
        const mapsInstances = {};
        
        // Fonction globale appelée par les boutons "Voir la carte"
        async function toggleTrajetMap(id, dTxt, aTxt) {
            const container = document.getElementById('map-container-' + id);
            const mapId = 'map-' + id;
            
            if(!container.classList.contains('hidden')) { 
                container.classList.add('hidden'); 
                return; 
            }
            
            container.classList.remove('hidden');
            
            // Si la carte existe déjà, on redimensionne juste
            if(mapsInstances[id]) { 
                setTimeout(() => mapsInstances[id].resize(), 100); 
                return; 
            }

            // Sinon on la crée
            try {
                // Fonction helper pour choper les coords
                const getCoords = async (query) => {
                    const res = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${query}&limit=1`);
                    const data = await res.json();
                    return data.features[0].geometry.coordinates;
                };

                const start = await getCoords(dTxt);
                const end = await getCoords(aTxt);

                const miniMap = new mapboxgl.Map({ 
                    container: mapId, 
                    style: 'mapbox://styles/mapbox/streets-v12', 
                    center: start, 
                    zoom: 10,
                    interactive: true 
                });
                
                miniMap.addControl(new mapboxgl.NavigationControl(), 'top-left');
                mapsInstances[id] = miniMap;
                
                new mapboxgl.Marker({ color: "#666" }).setLngLat(start).addTo(miniMap);
                new mapboxgl.Marker({ color: "#2E7D32" }).setLngLat(end).addTo(miniMap);
                
                // Tracer la route
                miniMap.on('load', async () => {
                    const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`;
                    const req = await fetch(url);
                    const json = await req.json();
                    const route = json.routes[0].geometry.coordinates;
                    
                    miniMap.addLayer({
                        id: 'route',
                        type: 'line',
                        source: { type: 'geojson', data: { type: 'Feature', properties: {}, geometry: { type: 'LineString', coordinates: route } } },
                        layout: { 'line-join': 'round', 'line-cap': 'round' },
                        paint: { 'line-color': '#3887be', 'line-width': 4, 'line-opacity': 0.8 }
                    });

                    // Fit bounds
                    const bounds = new mapboxgl.LngLatBounds(start, start);
                    route.forEach(c => bounds.extend(c));
                    miniMap.fitBounds(bounds, { padding: 40 });
                });

            } catch(e) { 
                console.error("Erreur carte trajet " + id, e); 
            }
        }

        // 3. MAIN MAP & AUTOCOMPLETE (Chargement page)
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr("#date", { locale: "fr", minDate: "today", dateFormat: "d/m/Y", disableMobile: "true" });
            flatpickr("#heure", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: "08:00" });

            const map = new mapboxgl.Map({ container: 'map', style: 'mapbox://styles/mapbox/streets-v12', center: [2.295, 49.894], zoom: 5 });
            map.addControl(new mapboxgl.NavigationControl(), 'top-right');

            // Autocomplete Logic
            function setupAutocomplete(idInput, idList, idHidden) {
                const input = document.getElementById(idInput);
                const list = document.getElementById(idList);
                const hidden = document.getElementById(idHidden);
                let timeout;

                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    const q = this.value;
                    if(q.length < 3) { list.classList.add('hidden'); return; }

                    timeout = setTimeout(() => {
                        fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=5`)
                        .then(r=>r.json()).then(d=>{
                            list.innerHTML='';
                            if(d.features && d.features.length>0){
                                list.classList.remove('hidden');
                                d.features.forEach(f=>{
                                    const li = document.createElement('li');
                                    li.className="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b text-sm";
                                    li.textContent=f.properties.label;
                                    li.onclick=()=>{ 
                                        input.value=f.properties.label; 
                                        hidden.value=f.geometry.coordinates; 
                                        list.classList.add('hidden'); 
                                        tracerRoute(); 
                                    };
                                    list.appendChild(li);
                                });
                            } else list.classList.add('hidden');
                        });
                    }, 300);
                });
                document.addEventListener('click', e=>{if(e.target!==input) list.classList.add('hidden');});
            }
            setupAutocomplete('depart', 'liste-depart', 'coords_depart');
            setupAutocomplete('arrivee', 'liste-arrivee', 'coords_arrivee');

            // Tracer Route Principale
            function tracerRoute() {
                const d = document.getElementById('coords_depart').value;
                const a = document.getElementById('coords_arrivee').value;
                if(d && a) {
                    const start=d.split(',').map(Number), end=a.split(',').map(Number);
                    // Clean markers
                    const markers = document.getElementsByClassName('mapboxgl-marker');
                    while(markers[0]) markers[0].parentNode.removeChild(markers[0]);
                    
                    new mapboxgl.Marker({color:"#666"}).setLngLat(start).addTo(map); 
                    new mapboxgl.Marker({color:"#2E7D32"}).setLngLat(end).addTo(map);

                    const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`;
                    fetch(url).then(r=>r.json()).then(json=>{
                        const route = json.routes[0].geometry.coordinates;
                        const geojson = { type: 'Feature', properties: {}, geometry: { type: 'LineString', coordinates: route } };
                        if(map.getSource('route')) map.getSource('route').setData(geojson);
                        else map.addLayer({ id: 'route', type: 'line', source: { type: 'geojson', data: geojson }, layout: {'line-join': 'round', 'line-cap': 'round'}, paint: {'line-color': '#2E7D32', 'line-width': 5} });
                        
                        const bounds = new mapboxgl.LngLatBounds(start, start);
                        route.forEach(c => bounds.extend(c));
                        map.fitBounds(bounds, { padding: 50 });
                    });
                }
            }
            map.on('load', tracerRoute);
        });
    </script>
@endsection