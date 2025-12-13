document.addEventListener('DOMContentLoaded', () => {

    //CONFIG MAPBOX
    const configDiv = document.getElementById('mapbox-config');
    if (configDiv) {
        mapboxgl.accessToken = configDiv.dataset.token;
    }

    //(A venir / Passé)
    window.changerOnglet = function (onglet) {
        const btnAvenir = document.getElementById('btn-avenir');
        const btnPasse = document.getElementById('btn-passe');
        const divAvenir = document.getElementById('content-avenir');
        const divPasse = document.getElementById('content-passe');

        if (onglet === 'avenir') {
            btnAvenir.className = "flex-1 py-2 rounded-lg font-bold text-sm bg-white text-[#2E7D32] shadow-sm";
            btnPasse.className = "flex-1 py-2 rounded-lg font-bold text-sm text-gray-500";
            divAvenir.classList.remove('hidden');
            divPasse.classList.add('hidden');
        } else {
            btnPasse.className = "flex-1 py-2 rounded-lg font-bold text-sm bg-white text-[#2E7D32] shadow-sm";
            btnAvenir.className = "flex-1 py-2 rounded-lg font-bold text-sm text-gray-500";
            divPasse.classList.remove('hidden');
            divAvenir.classList.add('hidden');
        }
    };

    //ANNULATION
    window.closeModal = function (id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    };

    window.openAnnulerModal = function (id) {
        const form = document.getElementById('form-annuler');
        const modal = document.getElementById('modal-annuler');

        if (form && modal) {
            form.action = `/trajet/annuler/${id}`;
            modal.classList.remove('hidden');
        }
    };

    //CARTES DES TRAJETS
    const mapsInstances = {};

    window.toggleTrajetMap = async function (id, dTxt, aTxt) {
        const container = document.getElementById('map-container-' + id);
        const mapId = 'map-' + id;

        if (!container) return;

        // Ouvrir / fermer la carte
        if (!container.classList.contains('hidden')) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');

        // Carte déjà créée
        if (mapsInstances[id]) {
            setTimeout(() => mapsInstances[id].resize(), 100);
            return;
        }

        try {
            const getCoords = async q => {
                if (!q) return null;
                if (q.toLowerCase().includes('iut amiens') || q.toLowerCase().includes('avenue des facultés')) {
                    return [2.263592, 49.873836];
                }
                const r = await fetch(`https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(q)}&limit=1`);
                const d = await r.json();
                return d.features?.[0]?.geometry.coordinates || null;
            };

            const start = await getCoords(dTxt);
            const end = await getCoords(aTxt);

            if (!start || !end) {
                document.getElementById(mapId).innerHTML = '<p class="text-red-500 text-center pt-10">Adresse introuvable</p>';
                return;
            }

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
                        data: {
                            type: 'Feature',
                            geometry: { type: 'LineString', coordinates: route }
                        }
                    },
                    layout: { 'line-join': 'round', 'line-cap': 'round' },
                    paint: { 'line-width': 4, 'line-opacity': 0.8 }
                });

                const bounds = new mapboxgl.LngLatBounds(start, start);
                route.forEach(c => bounds.extend(c));
                miniMap.fitBounds(bounds, { padding: 40 });
            });

        } catch (e) {
            console.error("Erreur chargement carte :", e);
        }
    };

});
