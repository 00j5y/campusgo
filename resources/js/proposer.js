//FONCTION DE SAUVEGARDE GLOBALE 
window.saveFormData = function() {
    const formData = {
        lieu_depart: document.getElementById('lieu_depart')?.value,
        lieu_arrivee: document.getElementById('lieu_arrivee')?.value,
        date_depart: document.getElementById('date_depart')?.value,
        heure_depart: document.getElementById('heure_depart')?.value,
        places_disponibles: document.getElementById('places_disponibles')?.value
    };
    localStorage.setItem('trajet_temp_data', JSON.stringify(formData));
};

document.addEventListener('DOMContentLoaded', function() {

    //VARIABLES
    const IUT_LABEL = "IUT Amiens, Avenue des Facultés";
    const IUT_COORDS = "2.263592,49.873836";
    const MAPBOX_TOKEN = "pk.eyJ1IjoiZ2FieXNjb3RlIiwiYSI6ImNtaXlueXBycDBlMnIzZnM3NDF0aWZ4emIifQ.Kv51hN4zyQ9O2AZLlbSdZg";

    const departInput = document.getElementById('lieu_depart');
    const arriveeInput = document.getElementById('lieu_arrivee');
    const coordsDepart = document.getElementById('coords_depart');
    const coordsArrivee = document.getElementById('coords_arrivee');
    const inverserButton = document.getElementById('btn-inverser-lieux');
    const useButton = document.getElementById('btn-utiliser'); 
    const dataContainer = document.getElementById('dernier-trajet-data'); 
    const form = document.getElementById('form-creation');

    let isProgrammaticChange = false; // Pour éviter les boucles infinies

    //CONFIGURATION Calendriers
    let datePicker = null;
    let timePicker = null;

    if(document.getElementById("date_depart")) {
        datePicker = flatpickr("#date_depart", {
            locale: "fr",
            minDate: "today",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",  
            disableMobile: true,
            allowInput: true
        });
    }

    if(document.getElementById("heure_depart")) {
        timePicker = flatpickr("#heure_depart", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
        });
    }

    
    // Gère l'état visuel et logique d'un champ 
    function setFieldState(input, hiddenInput, isBlocked, value = '', coords = '') {
        if(!input) return;

        input.readOnly = isBlocked;
        input.style.backgroundColor = isBlocked ? '#f3f4f6' : '#ffffff';
        input.style.cursor = isBlocked ? 'not-allowed' : 'text';

        if (isBlocked) {
            // Si bloqué -> C'est l'IUT
            input.value = IUT_LABEL;
            if(hiddenInput) hiddenInput.value = IUT_COORDS;
            input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
        } else {
            // Si débloqué -> On met la valeur demandée 
            if (value !== null) input.value = value;
            if (hiddenInput) hiddenInput.value = coords || '';
        }
    }

    //Sauvegarde les champs arrivée et départ en cas d'erreurs (old())
    function initializeFields() {
        if (departInput.value.trim() !== '' && departInput.value.trim() !== iutAdresse) {
            setInputState(arriveeInput, true);
        } 
        else if (arriveeInput.value.trim() !== '' && arriveeInput.value.trim() !== iutAdresse) {
            setInputState(departInput, true);
        }
        else {
            setInputState(arriveeInput, false, ''); 
            setInputState(departInput, false, '');
        }
    }

    //AUTOCOMPLETE
    function setupAutocomplete(inputId, listId, hiddenId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        const hidden = document.getElementById(hiddenId);
        let timeout = null;

        if (!input || !list) return;

        input.addEventListener('input', function() {
            if (isProgrammaticChange || input.readOnly) return;
            
            if(hidden) hidden.value = "";
            
            const query = this.value;
            clearTimeout(timeout);

            if (query.length < 3) {
                list.classList.add('hidden');
                return;
            }

            timeout = setTimeout(() => {
                fetch(`https://api-adresse.data.gouv.fr/search/?q=${query}&limit=5`)
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
                            li.className = "px-4 py-3 hover:bg-gray-100 cursor-pointer border-b text-sm text-gray-700";
                            li.textContent = f.properties.label;
                            
                            li.addEventListener('click', () => {
                                isProgrammaticChange = true;
                                input.value = f.properties.label;
                                if(hidden) hidden.value = f.geometry.coordinates;
                                list.classList.add('hidden');
                                isProgrammaticChange = false;
                                calculateDuration();
                                
                                // On applique la contrainte IUT 
                                handleConstraint(input === departInput ? 'depart' : 'arrivee');
                                
                                // Nettoyage erreurs
                                input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                                const errId = input === departInput ? 'error-lieu-depart' : 'error-lieu-arrivee';
                                document.getElementById(errId)?.classList.add('hidden');
                            });
                            
                            list.appendChild(li);
                        });
                    })
                    .catch(() => list.classList.add('hidden'));
            }, 300);
        });

        // Fermer la liste si on clique ailleurs
        document.addEventListener('click', e => {
            if (e.target !== input && e.target !== list) {
                list.classList.add('hidden');
            }
        });
    }

    setupAutocomplete('lieu_depart', 'liste-depart', 'coords_depart');
    setupAutocomplete('lieu_arrivee', 'liste-arrivee', 'coords_arrivee');

    //LOGIQUE CONTRAINTE IUT
    function handleConstraint(source) {
        if(isProgrammaticChange) return;

        if (source === 'depart' && departInput) {
            if (departInput.value.trim() !== '') {
                setFieldState(arriveeInput, coordsArrivee, true);
            } else {
                setFieldState(arriveeInput, coordsArrivee, false, '');
            }
        } else if (source === 'arrivee' && arriveeInput) {
            if (arriveeInput.value.trim() !== '') {
                setFieldState(departInput, coordsDepart, true);
            } else {
                setFieldState(departInput, coordsDepart, false, '');
            }
        }
        setTimeout(calculateDuration, 500);
    }

    if(departInput) departInput.addEventListener('input', () => handleConstraint('depart'));
    if(arriveeInput) arriveeInput.addEventListener('input', () => handleConstraint('arrivee'));

    //BOUTON INVERSER
    if (inverserButton) {
        inverserButton.addEventListener('click', function() {
            isProgrammaticChange = true;
            
            // On échange les valeurs, les coords et l'état ReadOnly
            const dVal = departInput.value;
            const dCoords = coordsDepart.value;
            const dReadOnly = departInput.readOnly;

            const aVal = arriveeInput.value;
            const aCoords = coordsArrivee.value;
            const aReadOnly = arriveeInput.readOnly;

            setFieldState(departInput, coordsDepart, aReadOnly, aVal, aCoords);
            setFieldState(arriveeInput, coordsArrivee, dReadOnly, dVal, dCoords);
            
            isProgrammaticChange = false;
        });
    }

    // BOUTON "UTILISER"
    if (useButton && dataContainer) {
        useButton.addEventListener('click', function() {
            try {
                isProgrammaticChange = true;

                // Récupération des attributs HTML injectés par Blade
                const prevDepart = dataContainer.getAttribute('js-depart') || '';
                const prevArrivee = dataContainer.getAttribute('js-arrivee') || '';
                const prevDate = dataContainer.getAttribute('js-date');
                const prevHeure = dataContainer.getAttribute('js-heure'); 
                const prevPlaces = dataContainer.getAttribute('js-places');
                const prevVehicule = dataContainer.getAttribute('js-vehicule');

                const departIsIUT = prevDepart.toLowerCase().includes('iut amiens') || prevDepart.toLowerCase().includes('facultés');
                
                if (departIsIUT) {
                    setFieldState(departInput, coordsDepart, true);
                    setFieldState(arriveeInput, coordsArrivee, false, prevArrivee); 
                } else {
                    setFieldState(arriveeInput, coordsArrivee, true);
                    setFieldState(departInput, coordsDepart, false, prevDepart);
                }

                if (prevDate && datePicker) {
                    // Si la date est passée, Flatpickr l'ignorera 
                    datePicker.setDate(prevDate, true);
                }
                
                if (prevHeure && timePicker) {
                    timePicker.setDate(prevHeure, true);
                }

                const placesSelect = document.getElementById('places_disponibles');
                const vehiculeSelect = document.getElementById('vehicule_id');
                
                if (placesSelect && prevPlaces) placesSelect.value = prevPlaces;
                if (vehiculeSelect && prevVehicule) vehiculeSelect.value = prevVehicule;

                [departInput, arriveeInput].forEach(input => {
                    if(input) {
                        input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                        const errId = input === departInput ? 'error-lieu-depart' : 'error-lieu-arrivee';
                        document.getElementById(errId)?.classList.add('hidden');
                    }
                });

            } catch (error) {
                console.error("Erreur remplissage auto :", error);
            } finally {
                isProgrammaticChange = false;
            }
        });
    }

    //VALIDATION DU FORMULAIRE
    if(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const errDep = document.getElementById('error-lieu-depart');
            const errArr = document.getElementById('error-lieu-arrivee');

            if(errDep) errDep.classList.add('hidden');
            if(errArr) errArr.classList.add('hidden');
            if(departInput) departInput.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
            if(arriveeInput) arriveeInput.classList.remove('border-red-500', 'ring-1', 'ring-red-500');

            if (departInput && departInput.value && !coordsDepart.value && departInput.value !== IUT_LABEL) {
                isValid = false;
                departInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                if(errDep) {
                    errDep.textContent = "Veuillez cliquer sur une suggestion pour valider l'adresse.";
                    errDep.classList.remove('hidden');
                }
            }

            if (arriveeInput && arriveeInput.value && !coordsArrivee.value && arriveeInput.value !== IUT_LABEL) {
                isValid = false;
                arriveeInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                if(errArr) {
                    errArr.textContent = "Veuillez cliquer sur une suggestion pour valider l'adresse.";
                    errArr.classList.remove('hidden');
                }
            }

            if (!isValid) {
                e.preventDefault(); 
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    }

    //RESTAURATION DES DONNÉES
    const savedData = localStorage.getItem('trajet_temp_data');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            
            setTimeout(() => {
                isProgrammaticChange = true;

                if (data.lieu_depart && departInput) departInput.value = data.lieu_depart;
                if (data.lieu_arrivee && arriveeInput) arriveeInput.value = data.lieu_arrivee;
                
                if (data.date_depart && datePicker) datePicker.setDate(data.date_depart);
                if (data.heure_depart && timePicker) timePicker.setDate(data.heure_depart);
                
                const places = document.getElementById('places_disponibles');
                if (places && data.places_disponibles) places.value = data.places_disponibles;

                if(departInput && departInput.value === IUT_LABEL) {
                    setFieldState(departInput, coordsDepart, true);
                    setFieldState(arriveeInput, coordsArrivee, false, data.lieu_arrivee);
                } else if (arriveeInput && arriveeInput.value === IUT_LABEL) {
                    setFieldState(arriveeInput, coordsArrivee, true);
                    setFieldState(departInput, coordsDepart, false, data.lieu_depart);
                }

                isProgrammaticChange = false;
                localStorage.removeItem('trajet_temp_data');
            }, 100);
        } catch(e) { console.error(e); }
    }
    async function calculateDuration() {
        const cDep = document.getElementById('coords_depart')?.value;
        const cArr = document.getElementById('coords_arrivee')?.value;
        const hiddenDuree = document.getElementById('duree_trajet');

        if (!cDep || !cArr || !hiddenDuree) return;

        const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${cDep};${cArr}?geometries=geojson&access_token=${MAPBOX_TOKEN}`;

        try {
            const req = await fetch(url);
            const json = await req.json();
            if (json.routes && json.routes.length > 0) {
                hiddenDuree.value = json.routes[0].duration; // Durée en secondes
            }
        } catch (e) { console.error(e); }
    }
});