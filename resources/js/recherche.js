document.addEventListener('DOMContentLoaded', () => {

    // CONFIG MAPBOX & DONNÉES INITIALES 
    const mapContainer = document.getElementById('map');
    const mapboxToken = mapContainer.dataset.token;
    const defaultCenter = JSON.parse(mapContainer.dataset.center);
    mapboxgl.accessToken = mapboxToken;

    const IUT_LABEL = "IUT Amiens, Avenue des Facultés";
    const IUT_COORDS = "2.263592,49.873836";

    // OUTILS DATE / HEURE 
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

    // CARTE PRINCIPALE 
    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: defaultCenter,
        zoom: 9
    });
    map.addControl(new mapboxgl.NavigationControl(), 'top-right');

    // CHAMPS & ÉTAT
    const departInput = document.getElementById('depart');
    const arriveeInput = document.getElementById('arrivee');
    const coordsDepart = document.getElementById('coords_depart');
    const coordsArrivee = document.getElementById('coords_arrivee');
    const inverserBtn = document.getElementById('btn-inverser-recherche');
    let isProgrammatic = false;

    // Verrouille / déverrouille un champ (contrainte IUT)
    function updateField(input, hidden, isLocked, val = '', co = '') {
        input.readOnly = isLocked;
        input.style.backgroundColor = isLocked ? '#f3f4f6' : '#ffffff';

        if (isLocked) {
            input.value = IUT_LABEL;
            hidden.value = IUT_COORDS;
        } else {
            if (val !== null) input.value = val;
            if (co !== null) hidden.value = co;
        }
    }

    // Autocomplete adresse avec sélection obligatoire
    function setupSearchAutocomplete(inputId, listId, hiddenId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        const hidden = document.getElementById(hiddenId);
        let t = null;

        if (!input) return;

        input.addEventListener('input', function () {
            if (isProgrammatic || input.readOnly) return;
            hidden.value = "";

            const q = this.value;
            clearTimeout(t);
            if (q.length < 3) { list.classList.add('hidden'); return; }

            t = setTimeout(() => {
                fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=5`)
                    .then(r => r.json())
                    .then(d => {
                        list.innerHTML = '';
                        if (!d.features?.length) { list.classList.add('hidden'); return; }
                        list.classList.remove('hidden');

                        d.features.forEach(f => {
                            const li = document.createElement('li');
                            li.className = "px-4 py-3 hover:bg-gray-100 cursor-pointer border-b text-sm";
                            li.textContent = f.properties.label;
                            li.onclick = () => {
                                isProgrammatic = true;
                                input.value = f.properties.label;
                                hidden.value = f.geometry.coordinates;
                                list.classList.add('hidden');
                                isProgrammatic = false;

                                applyConstraint(inputId === 'depart' ? 'depart' : 'arrivee');
                                verifierEtTracerRoute();
                            };
                            list.appendChild(li);
                        });
                    })
                    .catch(() => list.classList.add('hidden'));
            }, 300);
        });

        document.addEventListener('click', e => {
            if (e.target !== input && e.target !== list) list.classList.add('hidden');
        });
    }

    setupSearchAutocomplete('depart', 'liste-depart', 'coords_depart');
    setupSearchAutocomplete('arrivee', 'liste-arrivee', 'coords_arrivee');

    // Si un champ est rempli, l'autre devient IUT
    function applyConstraint(source) {
        if (isProgrammatic) return;

        if (source === 'depart') {
            departInput.value.trim()
                ? updateField(arriveeInput, coordsArrivee, true)
                : updateField(arriveeInput, coordsArrivee, false, '');
        } else {
            arriveeInput.value.trim()
                ? updateField(departInput, coordsDepart, true)
                : updateField(departInput, coordsDepart, false, '');
        }

        verifierEtTracerRoute();
    }

    departInput.addEventListener('input', () => applyConstraint('depart'));
    arriveeInput.addEventListener('input', () => applyConstraint('arrivee'));

    // Inversion départ / arrivée
    inverserBtn?.addEventListener('click', () => {
        isProgrammatic = true;

        const dV = departInput.value, dC = coordsDepart.value, dR = departInput.readOnly;
        const aV = arriveeInput.value, aC = coordsArrivee.value, aR = arriveeInput.readOnly;

        updateField(departInput, coordsDepart, aR, aV, aC);
        updateField(arriveeInput, coordsArrivee, dR, dV, dC);

        isProgrammatic = false;
        verifierEtTracerRoute();
    });

    // Validation : forcer sélection depuis l'autocomplete
    document.getElementById('form-recherche')?.addEventListener('submit', e => {
        let valid = true;

        if (!departInput.readOnly && departInput.value && !coordsDepart.value) valid = false;
        if (!arriveeInput.readOnly && arriveeInput.value && !coordsArrivee.value) valid = false;

        if (!valid) e.preventDefault();
    });

    //TRAÇAGE DE LA ROUTE 
    async function getRoute(start, end) {
        const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?geometries=geojson&access_token=${mapboxgl.accessToken}`;
        const json = await (await fetch(url)).json();
        if (!json.routes?.length) return;

        const route = json.routes[0].geometry.coordinates;
        const geojson = { type: 'Feature', geometry: { type: 'LineString', coordinates: route } };

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
    }

    function verifierEtTracerRoute() {
        if (!coordsDepart.value || !coordsArrivee.value) return;

        document.querySelectorAll('.mapboxgl-marker').forEach(m => m.remove());

        const start = coordsDepart.value.split(',').map(Number);
        const end = coordsArrivee.value.split(',').map(Number);

        new mapboxgl.Marker().setLngLat(start).addTo(map);
        new mapboxgl.Marker().setLngLat(end).addTo(map);

        getRoute(start, end);
    }

    map.on('load', verifierEtTracerRoute);

});
