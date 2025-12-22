document.addEventListener('DOMContentLoaded', () => {

    // --- VARIABLES & CONFIGURATION ---
    const mapContainer = document.getElementById('map');
    const mapboxToken = mapContainer.dataset.token;
    const defaultCenter = JSON.parse(mapContainer.dataset.center);

    const departInput = document.getElementById('depart');
    const arriveeInput = document.getElementById('arrivee');
    const coordsDepart = document.getElementById('coords_depart');
    const coordsArrivee = document.getElementById('coords_arrivee');
    const inverserBtn = document.getElementById('btn-inverser-recherche');
    const form = document.getElementById('form-recherche');

    // Variable pour éviter les boucles infinies lors des mises à jour automatiques
    let isProgrammatic = false;


    const IUT_AMIENS = {
        label: "IUT Amiens, Avenue des Facultés",
        coords: "2.263592,49.873836"
    };

    mapboxgl.accessToken = mapboxToken;

    function showInputError(input, errorId) {
        const errorEl = document.getElementById(errorId);
        if (input) {
            input.classList.remove('border-gray-200', 'focus:border-vert-principale', 'focus:ring-vert-principale');
            input.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
        }
        if (errorEl) {
            errorEl.innerText = "Veuillez cliquer sur une suggestion pour valider l'adresse.";
            errorEl.classList.remove('hidden');
        }
    }

    function clearInputError(input, errorId) {
        const errorEl = document.getElementById(errorId);
        if (input) {
            input.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            input.classList.add('border-gray-200', 'focus:border-vert-principale', 'focus:ring-vert-principale');
        }
        if (errorEl) {
            errorEl.classList.add('hidden');
        }
    }

    // OUTILS UI
    flatpickr("#date", {
        locale: "fr",
        minDate: "today",
        dateFormat: "d/m/Y",
        disableMobile: true,
        allowInput: true
    });

    flatpickr("#heure", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,

    });

    //CARTE PRINCIPALE
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: defaultCenter,
        zoom: 9
    });

    map.addControl(new mapboxgl.NavigationControl(), 'top-right');

    //NOUVELLES FONCTIONNALITÉS & LOGIQUE FORMULAIRE 

    function updateField(input, hidden, isLocked, val = '', co = '') {
        input.readOnly = isLocked;
        input.style.backgroundColor = isLocked ? '#f3f4f6' : '#ffffff'; 
        input.style.cursor = isLocked ? 'not-allowed' : 'text';

        if (isLocked) {
            input.value = IUT_AMIENS.label;
            hidden.value = IUT_AMIENS.coords;
        } else {
            if (val !== null) input.value = val;
            if (co !== null) hidden.value = co;
        }
    }

    function applyConstraint(source) {
        if (isProgrammatic) return;

        if (source === 'depart') {
            if (departInput.value.trim() !== '') {
                updateField(arriveeInput, coordsArrivee, true);
            } else {
                updateField(arriveeInput, coordsArrivee, false, '');
            }
        } else {
            if (arriveeInput.value.trim() !== '') {
                updateField(departInput, coordsDepart, true);
            } else {
                updateField(departInput, coordsDepart, false, '');
            }
        }
        verifierEtTracerRoute();
    }

    if (departInput) departInput.addEventListener('input', () => applyConstraint('depart'));
    if (arriveeInput) arriveeInput.addEventListener('input', () => applyConstraint('arrivee'));

    function setupAutocomplete(idInput, idList, idHidden) {
        const input = document.getElementById(idInput);
        const list = document.getElementById(idList);
        const hidden = document.getElementById(idHidden);
        const errorId = 'error-' + idInput; // ex: error-depart

        if (!input) return;

        let timeout = null;

        input.addEventListener('input', function () {
            // >>> AJOUT : Nettoyage erreur dès la frappe
            clearInputError(input, errorId);

            if (isProgrammatic || input.readOnly) return;

            hidden.value = ""; 

            const q = this.value;
            clearTimeout(timeout);

            if (q.length < 3) {
                list.classList.add('hidden');
                return;
            }

            timeout = setTimeout(() => {
                fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=5`)
                    .then(r => r.json())
                    .then(d => {
                        list.innerHTML = '';
                        if (!d.features || d.features.length === 0) {
                            list.classList.add('hidden');
                            return;
                        }
                        list.classList.remove('hidden');

                        d.features.forEach(f => {
                            const li = document.createElement('li');
                            li.className = "px-4 py-3 hover:bg-gray-100 cursor-pointer border-b text-sm";
                            li.textContent = f.properties.label;

                            li.onclick = () => {
                                isProgrammatic = true; 
                                input.value = f.properties.label;
                                hidden.value = f.geometry.coordinates;
                                
                                // >>> AJOUT : Validation OK, on nettoie l'erreur
                                clearInputError(input, errorId);
                                list.classList.add('hidden');
                                
                                isProgrammatic = false;

                                applyConstraint(idInput === 'depart' ? 'depart' : 'arrivee');
                                verifierEtTracerRoute();
                            };

                            list.appendChild(li);
                        });
                    })
                    .catch(() => list.classList.add('hidden'));
            }, 300);
        });

        document.addEventListener('click', e => {
            if (e.target !== input && e.target !== list) {
                list.classList.add('hidden');
            }
        });
    }

        

    setupAutocomplete('depart', 'liste-depart', 'coords_depart');
    setupAutocomplete('arrivee', 'liste-arrivee', 'coords_arrivee');

    if (inverserBtn) {
        inverserBtn.addEventListener('click', () => {
            isProgrammatic = true; 

            const dV = departInput.value, dC = coordsDepart.value, dR = departInput.readOnly;
            const aV = arriveeInput.value, aC = coordsArrivee.value, aR = arriveeInput.readOnly;

            updateField(departInput, coordsDepart, aR, aV, aC);
            updateField(arriveeInput, coordsArrivee, dR, dV, dC);

            isProgrammatic = false;
            verifierEtTracerRoute();
        });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            let hasError = false;

            // Vérification DÉPART
            if (!departInput.readOnly && departInput.value && !coordsDepart.value) {
                showInputError(departInput, 'error-depart');
                hasError = true;
            } else {
                clearInputError(departInput, 'error-depart');
            }

            // Vérification ARRIVÉE
            if (!arriveeInput.readOnly && arriveeInput.value && !coordsArrivee.value) {
                showInputError(arriveeInput, 'error-arrivee');
                hasError = true;
            } else {
                clearInputError(arriveeInput, 'error-arrivee');
            }

            if (hasError) {
                e.preventDefault(); 
            }
        });
    }

    //FONCTIONS CARTE (ROUTE & MODALES)

    // Modales UI
    window.closeModal = id => {
        document.getElementById(id).classList.add('hidden');
    };

    window.openReserverModal = url => {
        document.getElementById('form-reserver').action = url;
        document.getElementById('modal-reserver').classList.remove('hidden');
    };

    window.openAnnulerModal = url => {
        document.getElementById('form-annuler').action = url;
        document.getElementById('modal-annuler').classList.remove('hidden');
    };

    window.toggleVoirTout = () => {
        const btn = document.getElementById('btn-voir-tout');
        const hidden = document.querySelectorAll('.trajet-cache');
        let visible = false;

        hidden.forEach(el => {
            el.classList.toggle('hidden');
            visible = !el.classList.contains('hidden');
        });

        btn.innerText = visible
            ? "Voir moins"
            : "Voir tout (" + (hidden.length + 1) + ")";
    };

    // Calcul et affichage de la route principale
    async function getRoute(start, end) {
        const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?geometries=geojson&access_token=${mapboxgl.accessToken}`;
        
        try {
            const json = await (await fetch(url)).json();
            if (!json.routes || !json.routes.length) return;

            const route = json.routes[0].geometry.coordinates;
            const geojson = {
                type: 'Feature',
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
                    paint: { 'line-width': 5, 'line-opacity': 0.8 }
                });
            }

            const bounds = new mapboxgl.LngLatBounds(start, start);
            route.forEach(c => bounds.extend(c));
            map.fitBounds(bounds, { padding: 50 });
        } catch (e) {
            console.error("Erreur calcul itinéraire:", e);
        }
    }

    function verifierEtTracerRoute() {
        const d = document.getElementById('coords_depart').value;
        const a = document.getElementById('coords_arrivee').value;

        // Nettoyer les marqueurs existants
        document.querySelectorAll('.mapboxgl-marker').forEach(m => m.remove());

        if (!d || !a) {
             // Si pas de trajet complet, on supprime la route existante si elle existe
            if (map.getSource('route')) {
                map.getSource('route').setData({
                    type: 'Feature',
                    geometry: { type: 'LineString', coordinates: [] }
                });
            }
            return;
        }

        const start = d.split(',').map(Number);
        const end = a.split(',').map(Number);

        new mapboxgl.Marker().setLngLat(start).addTo(map);
        new mapboxgl.Marker().setLngLat(end).addTo(map);

        getRoute(start, end);
    }

    map.on('load', verifierEtTracerRoute);

    //CARTES DES MINI-TRAJETS
    const mapsInstances = {};

    window.toggleTrajetMap = async (id, dTxt, aTxt) => {
        const container = document.getElementById('map-container-' + id);
        const mapId = 'map-' + id;

        if (!container.classList.contains('hidden')) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');

        if (mapsInstances[id]) {
            setTimeout(() => mapsInstances[id].resize(), 100);
            return;
        }

        const getCoords = async adresse => {
            if (!adresse) return null;

            const lower = adresse.toLowerCase();
            if (lower.includes('iut amiens') || lower.includes('avenue des facultés')) {
                return [2.263592, 49.873836]; 
            }

            const r = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(adresse)}&limit=1`);
            const d = await r.json();
            return d.features?.[0]?.geometry.coordinates || null;
        };

        const start = await getCoords(dTxt);
        const end = await getCoords(aTxt);

        if (!start || !end) {
            document.getElementById(mapId).innerHTML =
                '<div class="flex items-center justify-center h-full text-red-500 text-sm">Adresse introuvable</div>';
            return;
        }

        const miniMap = new mapboxgl.Map({
            container: mapId,
            style: 'mapbox://styles/mapbox/streets-v12',
            center: start,
            zoom: 10
        });

        miniMap.on('load', () => setTimeout(() => miniMap.resize(), 300));
        miniMap.addControl(new mapboxgl.NavigationControl(), 'top-left');
        mapsInstances[id] = miniMap;

        new mapboxgl.Marker().setLngLat(start).addTo(miniMap);
        new mapboxgl.Marker().setLngLat(end).addTo(miniMap);

        miniMap.on('load', async () => {
            const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?geometries=geojson&access_token=${mapboxgl.accessToken}`;
            const json = await (await fetch(url)).json();

            if (!json.routes || !json.routes.length) return;

            const route = json.routes[0].geometry.coordinates;

            miniMap.addLayer({
                id: 'route',
                type: 'line',
                source: {
                    type: 'geojson',
                    data: { type: 'Feature', geometry: { type: 'LineString', coordinates: route } }
                },
                layout: { 'line-join': 'round', 'line-cap': 'round' },
                paint: { 'line-width': 4, 'line-opacity': 0.8 }
            });

            const bounds = new mapboxgl.LngLatBounds(start, start);
            route.forEach(c => bounds.extend(c));
            miniMap.fitBounds(bounds, { padding: 40 });
        });
    };

});