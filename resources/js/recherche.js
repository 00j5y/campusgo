document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. RÉCUPÉRATION DES VARIABLES DEPUIS LE HTML ---
    // On va chercher les données qu'on a glissées dans des attributs 'data-'
    const mapContainer = document.getElementById('map');
    const mapboxToken = mapContainer.dataset.token;
    const defaultCenter = JSON.parse(mapContainer.dataset.center); // [2.263592, 49.873836]
    
    mapboxgl.accessToken = mapboxToken;

    const IUT_AMIENS = {
        label: "IUT Amiens, Avenue des Facultés, Amiens",
        coords: "2.263592,49.873836" 
    };

    // --- 2. OUTILS UI (Flatpickr) ---
    flatpickr("#date", { locale: "fr", minDate: "today", dateFormat: "d/m/Y", disableMobile: "true", allowInput: true });
    flatpickr("#heure", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: "08:00" });

    // --- 3. CARTE PRINCIPALE ---
    const map = new mapboxgl.Map({ 
        container: 'map', 
        style: 'mapbox://styles/mapbox/streets-v12', 
        center: defaultCenter, 
        zoom: 9 
    });
    map.addControl(new mapboxgl.NavigationControl(), 'top-right');

    // --- 4. FONCTIONS UTILITAIRES ---
    window.closeModal = function(id) { 
        document.getElementById(id).classList.add('hidden'); 
    };
    
    window.openReserverModal = function(id) { 
        document.getElementById('form-reserver').action = `/trajet/reserver/${id}`; 
        document.getElementById('modal-reserver').classList.remove('hidden'); 
    };
    
    window.openAnnulerModal = function(id) { 
        document.getElementById('form-annuler').action = `/trajet/annuler/${id}`; 
        document.getElementById('modal-annuler').classList.remove('hidden'); 
    };
    
    window.toggleVoirTout = function() {
        const btn = document.getElementById('btn-voir-tout');
        const hidden = document.querySelectorAll('.trajet-cache');
        let isShowing = false;
        hidden.forEach(el => {
            if(el.classList.contains('hidden')) { el.classList.remove('hidden'); isShowing = true; }
            else { el.classList.add('hidden'); isShowing = false; }
        });
        btn.innerText = isShowing ? "Voir moins" : "Voir tout (" + (hidden.length + 1) + ")";
    };

    // --- 5. LOGIQUE AUTOCOMPLETE ---
    function setFieldToIUT(inputId, hiddenId) {
        document.getElementById(inputId).value = IUT_AMIENS.label;
        document.getElementById(hiddenId).value = IUT_AMIENS.coords;
    }

    function setupAutocomplete(idInput, idList, idHidden, otherInputId, otherHiddenId) {
        const input = document.getElementById(idInput);
        const list = document.getElementById(idList);
        const hidden = document.getElementById(idHidden);
        
        if(!input) return; // Sécurité si l'élément n'existe pas

        let timeout = null;

        input.addEventListener('input', function() {
            const q = this.value;
            clearTimeout(timeout);
            if(q.length < 3) { list.classList.add('hidden'); return; }

            timeout = setTimeout(() => {
                fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=5`)
                .then(r => r.json())
                .then(d => {
                    list.innerHTML = '';
                    if(d.features && d.features.length > 0) {
                        list.classList.remove('hidden');
                        d.features.forEach(f => {
                            const li = document.createElement('li'); 
                            li.className = "px-4 py-3 hover:bg-gray-100 cursor-pointer border-b text-sm transition-colors"; 
                            li.textContent = f.properties.label;
                            
                            li.onclick = () => { 
                                input.value = f.properties.label; 
                                hidden.value = f.geometry.coordinates; 
                                list.classList.add('hidden'); 

                                const isIUT = f.properties.label.toLowerCase().includes('avenue des facultés') || f.properties.label.toLowerCase().includes('iut amiens');
                                const otherInput = document.getElementById(otherInputId);

                                if (!isIUT) {
                                    setFieldToIUT(otherInputId, otherHiddenId);
                                } else {
                                    otherInput.value = '';
                                    document.getElementById(otherHiddenId).value = '';
                                }
                                verifierEtTracerRoute(); 
                            };
                            list.appendChild(li);
                        });
                    } else {
                        list.classList.add('hidden');
                    }
                })
                .catch(err => console.error("Erreur API adresse", err));
            }, 300);
        });

        document.addEventListener('click', e => {
            if(e.target !== input && e.target !== list) list.classList.add('hidden');
        });
    }

    setupAutocomplete('depart', 'liste-depart', 'coords_depart', 'arrivee', 'coords_arrivee');
    setupAutocomplete('arrivee', 'liste-arrivee', 'coords_arrivee', 'depart', 'coords_depart');


    // --- 6. ROUTE ET MAP ---
    async function getRoute(start, end) {
        try {
            const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`;
            const req = await fetch(url);
            const json = await req.json();

            if(json.routes && json.routes.length > 0) {
                const route = json.routes[0].geometry.coordinates;
                const geojson = { type: 'Feature', properties: {}, geometry: { type: 'LineString', coordinates: route } };
                
                if (map.getSource('route')) {
                    map.getSource('route').setData(geojson);
                } else {
                    map.addLayer({
                        id: 'route',
                        type: 'line',
                        source: { type: 'geojson', data: geojson },
                        layout: { 'line-join': 'round', 'line-cap': 'round' },
                        paint: { 'line-color': '#2E7D32', 'line-width': 5, 'line-opacity': 0.8 }
                    });
                }

                const bounds = new mapboxgl.LngLatBounds(start, start);
                route.forEach(c => bounds.extend(c));
                map.fitBounds(bounds, { padding: 50 });
            }
        } catch (e) { console.error("Erreur itinéraire Mapbox", e); }
    }

    function verifierEtTracerRoute() {
        const dStr = document.getElementById('coords_depart').value;
        const aStr = document.getElementById('coords_arrivee').value;
        
        if(dStr && aStr) {
            const markers = document.getElementsByClassName('mapboxgl-marker');
            while(markers[0]){ markers[0].parentNode.removeChild(markers[0]); }

            const start = dStr.split(',').map(Number);
            const end = aStr.split(',').map(Number);
            
            new mapboxgl.Marker({ color: "#666" }).setLngLat(start).addTo(map); 
            new mapboxgl.Marker({ color: "#2E7D32" }).setLngLat(end).addTo(map);
            
            getRoute(start, end);
        }
    }
    
    map.on('load', verifierEtTracerRoute);

    // --- 7. CARTES INDIVIDUELLES ---
    // On attache cette fonction à window pour qu'elle soit accessible depuis le HTML (onclick)
    const mapsInstances = {};
    window.toggleTrajetMap = async function(id, dTxt, aTxt) {
        const container = document.getElementById('map-container-' + id);
        const mapId = 'map-' + id;
        
        if(!container.classList.contains('hidden')) { container.classList.add('hidden'); return; }
        container.classList.remove('hidden');
        
        if(mapsInstances[id]) { setTimeout(() => mapsInstances[id].resize(), 100); return; }

        try {
            const getCoords = async (q) => (await (await fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=1`)).json()).features[0].geometry.coordinates;
            const start = await getCoords(dTxt);
            const end = await getCoords(aTxt);

            const miniMap = new mapboxgl.Map({ container: mapId, style: 'mapbox://styles/mapbox/streets-v12', center: start, zoom: 10, interactive: true });
            miniMap.addControl(new mapboxgl.NavigationControl(), 'top-left');
            mapsInstances[id] = miniMap;
            
            new mapboxgl.Marker({ color: "#666" }).setLngLat(start).addTo(miniMap);
            new mapboxgl.Marker({ color: "#2E7D32" }).setLngLat(end).addTo(miniMap);
            
            miniMap.on('load', async () => {
                const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`;
                const json = await (await fetch(url)).json();
                const route = json.routes[0].geometry.coordinates;
                miniMap.addLayer({
                    id: 'route',
                    type: 'line',
                    source: { type: 'geojson', data: { type: 'Feature', properties: {}, geometry: { type: 'LineString', coordinates: route } } },
                    layout: { 'line-join': 'round', 'line-cap': 'round' },
                    paint: { 'line-color': '#3887be', 'line-width': 4, 'line-opacity': 0.8 }
                });
                const bounds = new mapboxgl.LngLatBounds(start, start);
                route.forEach(c => bounds.extend(c));
                miniMap.fitBounds(bounds, { padding: 40 });
            });
        } catch(e) { console.error(e); }
    };

});