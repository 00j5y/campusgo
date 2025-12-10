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
                <h1 class="text-3xl font-semibold text-[#333] mb-2">Salut {{ $prenom }} ! Où voulez vous partir aujourd'hui ?</h1>
                <p class="text-gray-500">Trouvez un covoiturage en quelques clics.</p>
            </div>

            @foreach(['success' => 'green', 'error' => 'red'] as $type => $color)
                @if(session($type))
                    <div class="max-w-4xl mx-auto mb-6 bg-{{$color}}-100 border border-{{$color}}-400 text-{{$color}}-700 px-4 py-3 rounded relative">
                        {{ session($type) }}
                    </div>
                @endif
            @endforeach

            <div class="bg-white rounded-3xl shadow-xl p-8 max-w-4xl mx-auto mb-12">
                <form action="{{ route('rechercher') }}" method="GET" autocomplete="off" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" id="coords_depart" name="coords_depart" value="{{ request('coords_depart') }}">
                    <input type="hidden" id="coords_arrivee" name="coords_arrivee" value="{{ request('coords_arrivee') }}">
                    
                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="font-bold text-gray-700 text-sm mb-2 block">Départ</label>
                        <i class="fa-solid fa-location-dot absolute left-4 top-[2.8rem] text-gray-400"></i>
                        <input type="text" id="depart" name="depart" value="{{ request('depart') }}" placeholder="Ville..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-[#2E7D32] focus:outline-none">
                        <ul id="liste-depart" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50"></ul>
                    </div>

                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="font-bold text-gray-700 text-sm mb-2 block">Arrivée</label>
                        <i class="fa-solid fa-location-crosshairs absolute left-4 top-[2.8rem] text-[#2E7D32]"></i>
                        <input type="text" id="arrivee" name="arrivee" value="{{ request('arrivee') }}" placeholder="Ville..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:border-[#2E7D32] focus:outline-none">
                        <ul id="liste-arrivee" class="absolute w-full bg-white border mt-1 max-h-60 overflow-auto shadow-lg hidden z-50"></ul>
                    </div>

                    <div>
                        <label class="font-bold text-gray-700 text-sm mb-2 block">Date</label>
                        <input type="text" id="date" name="date" value="{{ request('date') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:border-[#2E7D32] focus:outline-none">
                    </div>
                    <div>
                        <label class="font-bold text-gray-700 text-sm mb-2 block">Heure</label>
                        <input type="text" id="heure" name="heure" value="{{ request('heure') }}" placeholder="08:00" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 px-4 focus:border-[#2E7D32] focus:outline-none">
                    </div>

                    <div class="col-span-1 md:col-span-2 mt-4">
                        @include('components.map')

                        <button type="submit" class="w-full bg-[#2E7D32] hover:bg-[#1b5e20] text-white font-bold py-3 rounded-xl transition shadow-md flex justify-center items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>

            @if($rechercheFaite)
                <div class="max-w-4xl mx-auto mb-16">
                    <h2 class="text-xl font-bold text-[#333] mb-6">Trajets disponibles ({{ $resultats->count() }})</h2>
                    @if($resultats->isEmpty())
                        <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-6xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-bold text-[#333]">Aucun trajet trouvé</h3>
                            <p class="text-gray-500 mt-2">Nous n'avons pas trouvé de trajet correspondant à vos critères. Essayez de modifier vos paramètres de recherche ou proposez votre propre trajet !</p>
                            <a href="{{ route('trajets.create') }}" class="mt-4 inline-block bg-[#2E7D32] text-white px-6 py-2 rounded-lg font-bold text-sm">Proposer un trajet</a>
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

            <div class="max-w-4xl mx-auto">
                <div class="mb-6 flex justify-between items-end">
                    <h2 class="text-xl font-bold text-[#333]">Vos Prochains Trajets</h2>
                    @if($mesTrajets->count() > 1)
                        <button id="btn-voir-tout" onclick="toggleVoirTout()" class="cursor-pointer text-sm text-[#2E7D32] font-semibold hover:underline">Voir tout ({{ $mesTrajets->count() }})</button>
                    @endif
                </div>

                @if($mesTrajets->isEmpty())
                    <div class="text-center py-6 bg-white rounded-2xl border border-gray-100 text-gray-500">Aucun trajet prévu.</div>
                @else
                    <div class="space-y-4">
                        @foreach($mesTrajets as $trajet)
                            <div class="{{ $loop->iteration > 1 ? 'hidden trajet-cache' : '' }}">
                                @include('components.trajet-card', ['trajet' => $trajet, 'mode' => 'perso'])
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <div class="bg-[#E0E5D5] rounded-3xl p-8 mt-16 text-center">
                <h3 class="font-bold text-[#333] text-lg mb-2">Prêt à partir ?</h3>
                <div class="flex justify-center gap-4 mt-4">
                    <a href="{{ route('trajets.create') }}" class="cursor-pointer bg-[#2E7D32] text-white px-6 py-2.5 rounded-lg font-bold text-sm">Proposer</a>
                    <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" class="cursor-pointer bg-white text-[#333] px-6 py-2.5 rounded-lg font-bold text-sm border">Rechercher</button>
                </div>
                <h4 class="text-gray-500 text-sm mt-4">Rejoignez la communauté Campus'Go et rendez vos trajets plus agréables.</h4>
            </div>

        </div>
    </main>

    @foreach(['reserver' => ['color'=>'green', 'icon'=>'check', 'title'=>'Confirmer', 'btn'=>'Réserver'], 
              'annuler' => ['color'=>'red', 'icon'=>'triangle-exclamation', 'title'=>'Annuler ?', 'btn'=>'Supprimer']] as $id => $conf)
    <div id="modal-{{$id}}" class="fixed inset-0 z-[9999] hidden">
        <div class="fixed inset-0 bg-gray-900/20 backdrop-blur-md" onclick="closeModal('modal-{{$id}}')"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl p-6 max-w-lg w-full text-center animate-fade-in-down">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-{{$conf['color']}}-100 mb-4">
                    <i class="fa-solid fa-{{$conf['icon']}} text-2xl text-{{$conf['color']}}-600"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">{{$conf['title']}}</h3>
                <form id="form-{{$id}}" action="" method="POST" class="flex gap-3 justify-center mt-6">
                    @csrf 
                    <button type="button" onclick="closeModal('modal-{{$id}}')" class="w-1/2 border px-5 py-3 rounded-xl font-bold">Retour</button>
                    <button type="submit" class="w-1/2 bg-{{$conf['color'] === 'green' ? '[#2E7D32]' : '[#FF5A5F]'}} text-white px-5 py-3 rounded-xl font-bold">{{$conf['btn']}}</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://api.mapbox.com/mapbox-gl-js/v3.9.4/mapbox-gl.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoiZ2FieXNjb3RlIiwiYSI6ImNtaXlueXBycDBlMnIzZnM3NDF0aWZ4emIifQ.Kv51hN4zyQ9O2AZLlbSdZg'; 

        // 1. CARTE PRINCIPALE
        const map = new mapboxgl.Map({ container: 'map', style: 'mapbox://styles/mapbox/streets-v12', center: [2.295, 49.894], zoom: 12 });
        map.addControl(new mapboxgl.NavigationControl());

        // 2. OUTILS UI
        flatpickr("#date", { locale: "fr", minDate: "today", dateFormat: "d/m/Y", disableMobile: "true" });
        flatpickr("#heure", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: "08:00" });

        function closeModal(id) { document.getElementById(id).classList.add('hidden'); }
        function openReserverModal(id) { document.getElementById('form-reserver').action = `/trajet/reserver/${id}`; document.getElementById('modal-reserver').classList.remove('hidden'); }
        function openAnnulerModal(id) { document.getElementById('form-annuler').action = `/trajet/annuler/${id}`; document.getElementById('modal-annuler').classList.remove('hidden'); }
        
        function toggleVoirTout() {
            const btn = document.getElementById('btn-voir-tout');
            const hidden = document.querySelectorAll('.trajet-cache');
            let isHidden = false;
            hidden.forEach(el => {
                if(el.classList.contains('hidden')) { el.classList.remove('hidden'); isHidden = true; }
                else { el.classList.add('hidden'); isHidden = false; }
            });
            btn.innerText = isHidden ? "Voir moins" : "Voir tout";
        }

        // 3. AUTOCOMPLETE & ROUTE
        function setupAutocomplete(idInput, idList, idHidden) {
            const input = document.getElementById(idInput);
            const list = document.getElementById(idList);
            const hidden = document.getElementById(idHidden);
            input.addEventListener('input', function() {
                const q = this.value;
                if(q.length < 3) { list.classList.add('hidden'); return; }
                fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=5`).then(r=>r.json()).then(d=>{
                    list.innerHTML='';
                    if(d.features.length>0){
                        list.classList.remove('hidden');
                        d.features.forEach(f=>{
                            const li = document.createElement('li'); li.className="px-4 py-3 hover:bg-gray-100 cursor-pointer border-b text-sm"; li.textContent=f.properties.label;
                            li.onclick=()=>{ input.value=f.properties.label; hidden.value=f.geometry.coordinates; list.classList.add('hidden'); verifierEtTracerRoute(); };
                            list.appendChild(li);
                        });
                    } else list.classList.add('hidden');
                });
            });
            document.addEventListener('click', e=>{if(e.target!==input) list.classList.add('hidden');});
        }
        setupAutocomplete('depart', 'liste-depart', 'coords_depart');
        setupAutocomplete('arrivee', 'liste-arrivee', 'coords_arrivee');

        async function getRoute(start, end, mapInstance = map, layerId = 'route', color = '#3887be') {
            const q = await fetch(`https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`);
            const json = await q.json();
            const route = json.routes[0].geometry.coordinates;
            const geojson = { type: 'Feature', properties: {}, geometry: { type: 'LineString', coordinates: route } };
            
            if (mapInstance.getSource(layerId)) mapInstance.getSource(layerId).setData(geojson);
            else mapInstance.addLayer({ id: layerId, type: 'line', source: { type: 'geojson', data: geojson }, layout: {'line-join': 'round', 'line-cap': 'round'}, paint: {'line-color': color, 'line-width': 5, 'line-opacity': 0.75} });

            const bounds = new mapboxgl.LngLatBounds(start, start);
            route.forEach(c => bounds.extend(c));
            mapInstance.fitBounds(bounds, { padding: 50 });
        }

        function verifierEtTracerRoute() {
            const d = document.getElementById('coords_depart').value, a = document.getElementById('coords_arrivee').value;
            if(d && a) {
                const start=d.split(',').map(Number), end=a.split(',').map(Number);
                new mapboxgl.Marker({color:"#666"}).setLngLat(start).addTo(map); new mapboxgl.Marker({color:"#2E7D32"}).setLngLat(end).addTo(map);
                getRoute(start, end);
            }
        }
        map.on('load', verifierEtTracerRoute);

        // 4. CARTES INDIVIDUELLES
        const mapsInstances = {};
        async function toggleTrajetMap(id, dTxt, aTxt) {
            const container = document.getElementById(`map-container-${id}`), mapId = `map-${id}`;
            if(!container.classList.contains('hidden')) { container.classList.add('hidden'); return; }
            container.classList.remove('hidden');
            if(mapsInstances[id]) { setTimeout(()=>mapsInstances[id].resize(), 50); return; }

            try {
                const getC = async (q) => (await (await fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=1`)).json()).features[0].geometry.coordinates;
                const s = await getC(dTxt), e = await getC(aTxt);
                const mm = new mapboxgl.Map({ container: mapId, style: 'mapbox://styles/mapbox/streets-v12', center: s, zoom: 10, interactive: true });
                mm.addControl(new mapboxgl.NavigationControl(), 'top-left');
                mapsInstances[id] = mm; setTimeout(()=>mm.resize(), 50);
                new mapboxgl.Marker({color:"#666"}).setLngLat(s).addTo(mm); new mapboxgl.Marker({color:"#2E7D32"}).setLngLat(e).addTo(mm);
                mm.on('load', ()=>getRoute(s, e, mm, 'route', '#3887be'));
            } catch(e) { console.error(e); }
        }
    </script>
@endsection