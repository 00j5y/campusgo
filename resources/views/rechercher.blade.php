@extends('layouts.app')

@section('title', 'Rechercher un trajet - Campus\'GO')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <main class="bg-beige-principale min-h-screen py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-semibold text-gris1 mb-2">
                    Bonjour ! Où voulez-vous partir aujourd'hui ?
                </h1>
                <p class="text-gris1/70">Trouvez un covoiturage en quelques clics.</p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8 max-w-4xl mx-auto">
                
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8" autocomplete="off">
            
                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="block text-sm font-bold text-gris1 mb-2">Départ</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                            <input type="text" id="depart" placeholder="Ex: 15 rue de la République, Amiens" 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-vert-principale transition">
                            
                            <ul id="liste-depart" class="absolute w-full bg-white border border-gray-200 rounded-xl mt-1 max-h-60 overflow-y-auto shadow-lg z-50 hidden"></ul>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 relative">
                        <label class="block text-sm font-bold text-gris1 mb-2">Arrivée</label>
                        <div class="relative">
                            <i class="fa-solid fa-location-crosshairs absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                            <input type="text" id="arrivee" placeholder="Ex: IUT Amiens, Avenue des Facultés" 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-vert-principale transition">
                            
                            <ul id="liste-arrivee" class="absolute w-full bg-white border border-gray-200 rounded-xl mt-1 max-h-60 overflow-y-auto shadow-lg z-50 hidden"></ul>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gris1 mb-2">Date</label>
                        <div class="relative">
                            <i class="fa-regular fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                            <input type="text" id="date" placeholder="Sélectionnez une date" 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-vert-principale transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gris1 mb-2">Heure</label>
                        <div class="relative">
                            <i class="fa-regular fa-clock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10"></i>
                            <input type="text" id="heure" placeholder="Heure de départ" 
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-12 pr-4 focus:outline-none focus:border-vert-principale transition">
                        </div>
                    </div>

                </form>

                <div class="mb-6">
                    <h3 class="text-lg text-gris1 mb-4 text-center font-medium">Visualiser la carte</h3>
                    <div id="map" class="h-80 w-full rounded-2xl border-4 border-[#C3AB79] z-0"></div>
                </div>

                <button class="w-full bg-vert-principale hover:bg-vert-principal-h text-white font-bold py-4 rounded-xl transition shadow-md flex justify-center items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Rechercher un Trajet
                </button>
            </div>

        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

    <script>
        // 1. CARTE
        var map = L.map('map').setView([49.894, 2.295], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19, attribution: '© OpenStreetMap'
        }).addTo(map);

        // 2. CALENDRIER
        flatpickr("#date", { locale: "fr", minDate: "today", dateFormat: "d/m/Y", disableMobile: "true" });
        flatpickr("#heure", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: "08:00" });

        // 3. FONCTION POUR GERER L'AUTOCOMPLETION (On l'écrit une fois, on l'utilise partout)
        function activerAutocomplete(idInput, idListe) {
            const input = document.getElementById(idInput);
            const liste = document.getElementById(idListe);

            input.addEventListener('input', function() {
                const query = this.value;
                if (query.length < 3) { liste.classList.add('hidden'); return; }

                fetch(`https://api-adresse.data.gouv.fr/search/?q=${query}&limit=5`)
                    .then(r => r.json())
                    .then(d => {
                        liste.innerHTML = '';
                        if (d.features.length > 0) {
                            liste.classList.remove('hidden');
                            d.features.forEach(f => {
                                const li = document.createElement('li');
                                li.className = "px-4 py-3 hover:bg-vert-second cursor-pointer text-sm text-gris1 border-b border-gray-100 last:border-0 transition";
                                li.textContent = f.properties.label;
                                li.onclick = () => { 
                                    input.value = f.properties.label; 
                                    liste.classList.add('hidden');
                                    
                                    // Petit bonus : Si c'est l'arrivée, on pourrait centrer la carte dessus plus tard !
                                };
                                liste.appendChild(li);
                            });
                        } else { liste.classList.add('hidden'); }
                    });
            });

            // Cacher la liste si on clique ailleurs
            document.addEventListener('click', e => { if(e.target !== input) liste.classList.add('hidden'); });
        }

        // On active l'autocomplétion sur les DEUX champs maintenant !
        activerAutocomplete('depart', 'liste-depart');
        activerAutocomplete('arrivee', 'liste-arrivee');

    </script>
@endsection