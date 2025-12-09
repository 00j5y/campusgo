@extends('layouts.app')

@section('title', 'Rechercher un trajet - Campus\'GO')

@section('content')
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <main class="bg-[#F3EDE3] min-h-screen py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-semibold text-[#333] mb-2">
                    Salut {{ $prenom }} ! Où voulez-vous partir aujourd'hui ?
                </h1>
                <p class="text-gray-500">Trouvez un covoiturage en quelques clics.</p>
            </div>

            @if(session('success'))
                <div class="max-w-4xl mx-auto mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative animate-fade-in-down">
                    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="max-w-4xl mx-auto mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative animate-fade-in-down">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8 max-w-4xl mx-auto mb-12">
                <form action="{{ route('rechercher') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8" autocomplete="off">
                    
                    <input type="hidden" id="coords_depart" name="coords_depart" value="{{ request('coords_depart') }}">
                    <input type="hidden" id="coords_arrivee" name="coords_arrivee" value="{{ request('coords_arrivee') }}">
                    
                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Départ</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                            <input type="text" id="depart" name="depart" value="{{ request('depart') }}" placeholder="Ville, adresse..." 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-[#2E7D32]">
                             <ul id="liste-depart" class="absolute w-full bg-white border border-gray-200 rounded-xl mt-1 max-h-60 overflow-y-auto shadow-lg z-50 hidden"></ul>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Arrivée</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-crosshairs absolute left-4 top-1/2 -translate-y-1/2 text-[#2E7D32] z-10"></i>
                            <input type="text" id="arrivee" name="arrivee" value="{{ request('arrivee') }}" placeholder="Ville, adresse..." 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-[#2E7D32]">
                            <ul id="liste-arrivee" class="absolute w-full bg-white border border-gray-200 rounded-xl mt-1 max-h-60 overflow-y-auto shadow-lg z-50 hidden"></ul>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Date</label>
                        <div class="relative">
                            <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                            <input type="text" id="date" name="date" value="{{ request('date') }}" placeholder="Sélectionnez une date" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-[#2E7D32]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Heure</label>
                        <div class="relative">
                            <i class="fa-regular fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                            <input type="text" id="heure" name="heure" value="{{ request('heure') }}" placeholder="08:00" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-[#2E7D32]">
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 mt-4">
                        <div class="mb-6">
                            <h3 class="text-lg text-gray-600 mb-4 text-center font-medium">Visualiser la carte</h3>
                            <div id="map" class="h-80 w-full rounded-2xl border-4 border-[#C3AB79] overflow-hidden"></div>
                        </div>

                        <button type="submit" class="w-full bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-3 rounded-xl transition shadow-md flex justify-center items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass"></i> Rechercher un Trajet
                        </button>
                    </div>
                </form>
            </div>

            @if($rechercheFaite)
                <div class="max-w-4xl mx-auto mb-16">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-[#333]">Trajets disponibles (Recherche)</h2>
                        <p class="text-sm text-gray-500">{{ $resultats->count() }} trajet(s) trouvé(s)</p>
                    </div>

                    @if($resultats->isEmpty())
                        <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                            <div class="mb-6 flex justify-center">
                                <i class="fa-solid fa-magnifying-glass text-6xl text-gray-400"></i> 
                            </div>
                            <h3 class="text-lg font-bold text-[#333] mb-2">Aucun trajet trouvé</h3>
                            <p class="text-gray-500 text-sm max-w-md mx-auto mb-6">
                                Nous n'avons pas trouvé de trajet correspondant à vos critères.
                            </p>
                            <a href="#" class="inline-block bg-[#2E7D32] text-white px-6 py-2 rounded-lg font-medium text-sm hover:bg-[#1b5e20] transition">
                                Proposer un trajet
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($resultats as $trajet)
                                <div class="bg-white border border-[#2E7D32] rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                                    <div class="flex flex-col md:flex-row justify-between items-center">
                                        <div class="flex-grow space-y-2">
                                            <div class="flex items-center gap-3">
                                                <i class="fa-solid fa-location-dot text-[#2E7D32]"></i>
                                                <span class="font-bold text-[#333]">{{ $trajet->Lieu_Depart }}</span>
                                                <i class="fa-solid fa-arrow-right text-gray-400 text-xs"></i>
                                                <span class="text-[#333]">{{ $trajet->Lieu_Arrivee }}</span>
                                            </div>
                                            <div class="flex gap-4 text-sm text-gray-600">
                                                <span><i class="far fa-calendar"></i> {{ \Carbon\Carbon::parse($trajet->Date_)->format('d/m/Y') }}</span>
                                                <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($trajet->Heure_Depart)->format('H:i') }}</span>
                                                <span class="text-[#2E7D32] font-bold">{{ $trajet->Prix }}€</span>
                                            </div>
                                        </div>
                                        <button onclick="openReserverModal({{ $trajet->ID_Trajet }})" 
                                                class="bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-2 px-6 rounded-lg transition mt-4 md:mt-0">
                                            Choisir ce trajet
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <div class="max-w-4xl mx-auto">
                
                <div class="mb-6 flex justify-between items-end">
                    <h2 class="text-xl font-bold text-[#333]">Vos Prochains Trajets</h2>
                    @if($mesTrajets->count() > 1)
                        <button id="btn-voir-tout" onclick="toggleVoirTout()" class="text-sm text-[#2E7D32] font-semibold hover:underline focus:outline-none">
                            Voir tout ({{ $mesTrajets->count() }})
                        </button>
                    @endif
                </div>

                @if($mesTrajets->isEmpty())
                    <div class="text-center py-6 bg-white rounded-2xl border border-gray-100">
                        <p class="text-gray-500">Vous n'avez aucun trajet prévu.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($mesTrajets as $trajet)
                            <div class="bg-white border border-[#2E7D32] rounded-2xl p-6 shadow-sm relative overflow-hidden transition-all duration-300 {{ $loop->iteration > 1 ? 'hidden trajet-cache' : '' }}">
                                
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
                                        </div>
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
                                    </div>

                                    <div class="mt-4 md:mt-0 flex flex-col gap-2 w-full md:w-[160px]">
                                        <button onclick="toggleTrajetMap('{{ $trajet->ID_Trajet }}', '{{ e($trajet->Lieu_Depart) }}', '{{ e($trajet->Lieu_Arrivee) }}')"
                                                class="w-full border border-[#2E7D32] text-[#2E7D32] hover:bg-[#2E7D32] hover:text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                                            Voir la carte
                                        </button>
                                        <button onclick="openAnnulerModal({{ $trajet->ID_Trajet }})" 
                                                class="w-full bg-[#FF5A5F] hover:bg-[#E0484D] text-white font-bold py-2 px-4 rounded-lg transition text-sm text-center">
                                            Annuler
                                        </button>
                                    </div>
                                </div>

                                <div id="map-container-{{ $trajet->ID_Trajet }}" class="hidden mt-6 pt-4 border-t border-gray-100">
                                    <p class="text-xs text-gray-400 mb-2">Itinéraire estimé :</p>
                                    <div id="map-{{ $trajet->ID_Trajet }}" class="h-64 w-full rounded-xl overflow-hidden border border-gray-200"></div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="bg-[#E0E5D5] rounded-3xl p-8 mt-16 text-center">
                <h3 class="font-bold text-[#333] text-lg mb-2">Prêt à partir ?</h3>
                <p class="text-sm text-gray-600 mb-6">Rejoignez la communauté Campus'Go et rendez vos trajets plus agréables.</p>
                <div class="flex justify-center gap-4">
                    <a href="#" class="bg-[#2E7D32] hover:bg-[#1b5e20] text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-md transition">
                        Proposer un Trajet
                    </a>
                    <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' });"
                     class="bg-white hover:bg-gray-50 text-[#333] px-6 py-2.5 rounded-lg font-bold text-sm shadow-sm border border-gray-200 transition">
                        Rechercher un Trajet
                    </button>
                </div>
            </div>

        </div>
    </main>

    <div id="modal-reserver" class="fixed inset-0 z-[9999] hidden">
        <div class="fixed inset-0 bg-gray-900/20 backdrop-blur-md transition-opacity" onclick="closeModal('modal-reserver')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-6 border border-gray-100 animate-fade-in-down">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                            <i class="fa-solid fa-check text-2xl text-[#2E7D32]"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Confirmer la réservation</h3>
                        <p class="text-sm text-gray-500 mb-6">Voulez-vous vraiment réserver une place ?</p>
                        <form id="form-reserver" action="" method="POST" class="flex gap-3 justify-center">
                            @csrf 
                            <button type="button" onclick="closeModal('modal-reserver')" class="w-1/2 bg-white text-gray-700 border border-gray-300 px-5 py-3 rounded-xl font-bold hover:bg-gray-50 transition">Annuler</button>
                            <button type="submit" class="w-1/2 bg-[#2E7D32] text-white px-5 py-3 rounded-xl font-bold hover:bg-[#1b5e20] transition shadow-md">Réserver</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-annuler" class="fixed inset-0 z-[9999] hidden">
        <div class="fixed inset-0 bg-gray-900/20 backdrop-blur-md transition-opacity" onclick="closeModal('modal-annuler')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg p-6 border border-gray-100 animate-fade-in-down">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                            <i class="fa-solid fa-triangle-exclamation text-2xl text-[#FF5A5F]"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Annuler ce trajet ?</h3>
                        <p class="text-sm text-gray-500 mb-6">Cette action est irréversible.</p>
                        <form id="form-annuler" action="" method="POST" class="flex gap-3 justify-center">
                            @csrf
                            <button type="button" onclick="closeModal('modal-annuler')" class="w-1/2 bg-white text-gray-700 border border-gray-300 px-5 py-3 rounded-xl font-bold hover:bg-gray-50 transition">Retour</button>
                            <button type="submit" class="w-1/2 bg-[#FF5A5F] text-white px-5 py-3 rounded-xl font-bold hover:bg-[#E0484D] transition shadow-md">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
    
    <script>
        // CONFIGURATION
        mapboxgl.accessToken = 'pk.eyJ1IjoiZ2FieXNjb3RlIiwiYSI6ImNtaXlueXBycDBlMnIzZnM3NDF0aWZ4emIifQ.Kv51hN4zyQ9O2AZLlbSdZg'; 
        
        // --- 1. CARTE PRINCIPALE (Recherche) ---
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [2.295, 49.894], // Centré sur Amiens
            zoom: 12
        });
        map.addControl(new mapboxgl.NavigationControl());

        // --- 2. CONFIGURATION FLATPICKR (Calendrier) ---
        flatpickr("#date", { locale: "fr", minDate: "today", dateFormat: "d/m/Y", disableMobile: "true" });
        flatpickr("#heure", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: "08:00" });

        // --- 3. AUTOCOMPLETION & GEOLOCALISATION ---
        function setupAutocomplete(idInput, idList, idHidden) {
            const input = document.getElementById(idInput);
            const list = document.getElementById(idList);
            const hiddenInput = document.getElementById(idHidden);

            input.addEventListener('input', function() {
                const q = this.value;
                if(q.length < 3) { list.classList.add('hidden'); return; }
                
                fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=5`)
                .then(r => r.json())
                .then(d => {
                    list.innerHTML='';
                    if(d.features.length > 0){
                        list.classList.remove('hidden');
                        d.features.forEach(f => {
                            const li = document.createElement('li');
                            li.className = "px-4 py-3 hover:bg-gray-100 cursor-pointer text-sm border-b";
                            li.textContent = f.properties.label;
                            li.onclick = () => {
                                input.value = f.properties.label;
                                hiddenInput.value = f.geometry.coordinates; // Stockage GPS [lon, lat]
                                list.classList.add('hidden');
                                verifierEtTracerRoute(); // Tracé auto si les 2 points sont là
                            };
                            list.appendChild(li);
                        });
                    } else { list.classList.add('hidden'); }
                });
            });
            document.addEventListener('click', e => { if(e.target !== input) list.classList.add('hidden'); });
        }

        setupAutocomplete('depart', 'liste-depart', 'coords_depart');
        setupAutocomplete('arrivee', 'liste-arrivee', 'coords_arrivee');

        // --- 4. TRACÉ DE ROUTE (Carte Principale) ---
        async function getRoute(start, end) {
            const query = await fetch(
                `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`
            );
            const json = await query.json();
            const data = json.routes[0];
            const route = data.geometry.coordinates;

            const geojson = {
                type: 'Feature',
                properties: {},
                geometry: { type: 'LineString', coordinates: route }
            };

            if (map.getSource('route')) {
                map.getSource('route').setData(geojson);
            } else {
                map.addLayer({
                    id: 'route',
                    type: 'line',
                    source: { type: 'geojson', data: geojson },
                    layout: { 'line-join': 'round', 'line-cap': 'round' },
                    paint: { 'line-color': '#3887be', 'line-width': 5, 'line-opacity': 0.75 }
                });
            }

            const bounds = new mapboxgl.LngLatBounds(start, start);
            route.forEach(coord => bounds.extend(coord));
            map.fitBounds(bounds, { padding: 50 });
        }

        function verifierEtTracerRoute() {
            const departVal = document.getElementById('coords_depart').value;
            const arriveeVal = document.getElementById('coords_arrivee').value;

            if (departVal && arriveeVal) {
                const start = departVal.split(',').map(Number);
                const end = arriveeVal.split(',').map(Number);
                
                new mapboxgl.Marker({ color: "#666" }).setLngLat(start).addTo(map);
                new mapboxgl.Marker({ color: "#2E7D32" }).setLngLat(end).addTo(map);
                getRoute(start, end);
            }
        }

        map.on('load', () => { verifierEtTracerRoute(); });


        // --- 5. GESTION DES CARTES INDIVIDUELLES (Toggle) ---
        const mapsInstances = {}; 

        async function toggleTrajetMap(id, departTxt, arriveeTxt) {
            const container = document.getElementById(`map-container-${id}`);
            const mapId = `map-${id}`;

            // Ouverture / Fermeture
            if (!container.classList.contains('hidden')) {
                container.classList.add('hidden');
                return;
            }
            container.classList.remove('hidden'); 

            // Si carte déjà créée, on resize juste (Fix gris Mapbox)
            if (mapsInstances[id]) {
                setTimeout(() => { mapsInstances[id].resize(); }, 50);
                return;
            }

            // Création de la carte
            try {
                const getCoords = async (query) => {
                    const r = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${query}&limit=1`);
                    const d = await r.json();
                    if (!d.features || d.features.length === 0) throw new Error("Adresse introuvable");
                    return d.features[0].geometry.coordinates;
                };

                const start = await getCoords(departTxt);
                const end = await getCoords(arriveeTxt);

                const miniMap = new mapboxgl.Map({
                    container: mapId,
                    style: 'mapbox://styles/mapbox/streets-v12',
                    center: start,
                    zoom: 10,
                    interactive: true
                });
                
                miniMap.addControl(new mapboxgl.NavigationControl(), 'top-left');
                mapsInstances[id] = miniMap;
                setTimeout(() => { miniMap.resize(); }, 50);

                new mapboxgl.Marker({ color: "#666" }).setLngLat(start).addTo(miniMap);
                new mapboxgl.Marker({ color: "#2E7D32" }).setLngLat(end).addTo(miniMap);

                miniMap.on('load', async () => {
                    const query = await fetch(
                        `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`
                    );
                    const json = await query.json();
                    
                    if (json.routes && json.routes.length > 0) {
                        const route = json.routes[0].geometry.coordinates;
                        miniMap.addLayer({
                            id: 'route',
                            type: 'line',
                            source: {
                                type: 'geojson',
                                data: { type: 'Feature', properties: {}, geometry: { type: 'LineString', coordinates: route } }
                            },
                            layout: { 'line-join': 'round', 'line-cap': 'round' },
                            paint: { 'line-color': '#3887be', 'line-width': 4, 'line-opacity': 0.8 }
                        });
                        const bounds = new mapboxgl.LngLatBounds(start, start);
                        route.forEach(coord => bounds.extend(coord));
                        miniMap.fitBounds(bounds, { padding: 40 });
                    }
                });

            } catch (error) {
                console.error("Erreur carte :", error);
                document.getElementById(mapId).innerHTML = `<div class="flex items-center justify-center h-full text-gray-400 text-sm">Impossible de localiser l'adresse.</div>`;
            }
        }

        // --- 6. GESTION "VOIR TOUT" ---
        function toggleVoirTout() {
            const trajetsCaches = document.querySelectorAll('.trajet-cache');
            const btn = document.getElementById('btn-voir-tout');
            let estCache = false;

            trajetsCaches.forEach(el => {
                if (el.classList.contains('hidden')) {
                    el.classList.remove('hidden');
                    estCache = true;
                } else {
                    el.classList.add('hidden');
                    estCache = false;
                }
            });

            btn.innerText = estCache ? "Voir moins" : "Voir tout";
        }

        // --- 7. GESTION MODALS ---
        function openReserverModal(id) {
            document.getElementById('form-reserver').action = `/trajet/reserver/${id}`;
            document.getElementById('modal-reserver').classList.remove('hidden');
        }

        function openAnnulerModal(id) {
            document.getElementById('form-annuler').action = `/trajet/annuler/${id}`;
            document.getElementById('modal-annuler').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
@endsection