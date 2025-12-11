document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. CONFIG MAPBOX ---
    const configDiv = document.getElementById('mapbox-config');
    // On vérifie que la div existe pour éviter les erreurs
    if (configDiv) {
        mapboxgl.accessToken = configDiv.dataset.token;
    }

    // --- 2. GESTION DES ONGLETS ---
    window.changerOnglet = function(onglet) {
        const btnAvenir = document.getElementById('btn-avenir');
        const btnPasse = document.getElementById('btn-passe');
        const divAvenir = document.getElementById('content-avenir');
        const divPasse = document.getElementById('content-passe');

        if (onglet === 'avenir') {
            // Style Active pour Avenir
            btnAvenir.className = "flex-1 py-2 rounded-lg font-bold text-sm transition-all bg-white text-[#2E7D32] shadow-sm";
            btnPasse.className = "flex-1 py-2 rounded-lg font-bold text-sm text-gray-500 hover:text-[#333] transition-all";
            
            // Affichage
            divAvenir.classList.remove('hidden');
            divPasse.classList.add('hidden');
        } else {
            // Style Active pour Passé
            btnPasse.className = "flex-1 py-2 rounded-lg font-bold text-sm transition-all bg-white text-[#2E7D32] shadow-sm";
            btnAvenir.className = "flex-1 py-2 rounded-lg font-bold text-sm text-gray-500 hover:text-[#333] transition-all";
            
            // Affichage
            divPasse.classList.remove('hidden');
            divAvenir.classList.add('hidden');
        }
    };

    // --- 3. MODALES (Annuler) ---
    window.closeModal = function(id) { 
        const el = document.getElementById(id);
        if(el) el.classList.add('hidden'); 
    };

    window.openAnnulerModal = function(id) { 
        const form = document.getElementById('form-annuler');
        const modal = document.getElementById('modal-annuler');
        if(form && modal) {
            // Met à jour l'URL d'action du formulaire avec l'ID du trajet
            form.action = `/trajet/annuler/${id}`; 
            modal.classList.remove('hidden'); 
        }
    };

    // --- 4. GESTION DES CARTES (Voir la carte) ---
    const mapsInstances = {};

    window.toggleTrajetMap = async function(id, dTxt, aTxt) {
        const container = document.getElementById('map-container-' + id);
        const mapId = 'map-' + id;
        
        if(!container) return;

        // Si déjà ouvert, on ferme
        if(!container.classList.contains('hidden')) { 
            container.classList.add('hidden'); 
            return; 
        }
        
        // Sinon on ouvre
        container.classList.remove('hidden');
        
        // Si la carte existe déjà, on redimensionne juste
        if(mapsInstances[id]) { 
            setTimeout(() => mapsInstances[id].resize(), 100); 
            return; 
        }

        // Sinon on crée la carte (Appel API Gouv pour les coords)
        try {
            const getCoords = async (q) => (await (await fetch(`https://api-adresse.data.gouv.fr/search/?q=${q}&limit=1`)).json()).features[0].geometry.coordinates;
            
            // Récupération des coordonnées Départ / Arrivée
            const start = await getCoords(dTxt);
            const end = await getCoords(aTxt);

            // Création de la mini map
            const miniMap = new mapboxgl.Map({ 
                container: mapId, 
                style: 'mapbox://styles/mapbox/streets-v12', 
                center: start, 
                zoom: 10, 
                interactive: true 
            });
            
            miniMap.addControl(new mapboxgl.NavigationControl(), 'top-left');
            mapsInstances[id] = miniMap;
            
            // Ajout des marqueurs
            new mapboxgl.Marker({ color: "#666" }).setLngLat(start).addTo(miniMap);
            new mapboxgl.Marker({ color: "#2E7D32" }).setLngLat(end).addTo(miniMap);
            
            // Tracé de la route une fois la carte chargée
            miniMap.on('load', async () => {
                const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${start[0]},${start[1]};${end[0]},${end[1]}?steps=true&geometries=geojson&access_token=${mapboxgl.accessToken}`;
                const json = await (await fetch(url)).json();
                
                if(json.routes && json.routes.length > 0) {
                    const route = json.routes[0].geometry.coordinates;
                    
                    miniMap.addLayer({
                        id: 'route',
                        type: 'line',
                        source: { type: 'geojson', data: { type: 'Feature', properties: {}, geometry: { type: 'LineString', coordinates: route } } },
                        layout: { 'line-join': 'round', 'line-cap': 'round' },
                        paint: { 'line-color': '#3887be', 'line-width': 4, 'line-opacity': 0.8 }
                    });
                    
                    // Ajuster la vue pour voir tout le trajet
                    const bounds = new mapboxgl.LngLatBounds(start, start);
                    route.forEach(c => bounds.extend(c));
                    miniMap.fitBounds(bounds, { padding: 40 });
                }
            });

        } catch(e) { 
            console.error("Erreur chargement carte : ", e); 
        }
    };

});