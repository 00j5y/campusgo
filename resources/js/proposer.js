window.saveFormData = function() {
    const formData = {
        lieu_depart: document.getElementById('lieu_depart')?.value,
        coords_depart: document.getElementById('coords_depart')?.value,
        lieu_arrivee: document.getElementById('lieu_arrivee')?.value,
        coords_arrivee: document.getElementById('coords_arrivee')?.value,
        date_depart: document.getElementById('date_depart')?.value,
        heure_depart: document.getElementById('heure_depart')?.value,
        places_disponibles: document.getElementById('places_disponibles')?.value,
        prix: document.getElementById('prix')?.value
    };
    localStorage.setItem('trajet_temp_data', JSON.stringify(formData));
};

document.addEventListener('DOMContentLoaded', function() {

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

    let isProgrammaticChange = false;

    let datePicker = null;
    let timePicker = null;

    if(document.getElementById("heure_depart")) {
        timePicker = flatpickr("#heure_depart", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            disableMobile: "true"
        });
    }

    if(document.getElementById("date_depart")) {
        datePicker = flatpickr("#date_depart", {
            locale: "fr",
            minDate: "today",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",  
            disableMobile: "true",
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                if (!timePicker || selectedDates.length === 0) return;

                const selectedDate = selectedDates[0];
                const now = new Date();
                const isToday = selectedDate.getDate() === now.getDate() &&
                                selectedDate.getMonth() === now.getMonth() &&
                                selectedDate.getFullYear() === now.getFullYear();

                if (isToday) {
                    const currentHour = now.getHours();
                    const currentMinute = now.getMinutes();
                    timePicker.set('minTime', `${currentHour}:${currentMinute}`);
                    
                    if (timePicker.selectedDates.length > 0) {
                        const selectedTime = timePicker.selectedDates[0];
                        const timeToCheck = new Date(); 
                        timeToCheck.setHours(selectedTime.getHours(), selectedTime.getMinutes(), 0, 0);
                        if (timeToCheck < now) {
                            timePicker.clear();
                        }
                    }
                } else {
                    timePicker.set('minTime', null);
                }
            }
        });
    }

    function setFieldState(input, hiddenInput, isBlocked, value = '', coords = '') {
        if(!input) return;

        input.readOnly = isBlocked;
        input.style.backgroundColor = isBlocked ? '#f3f4f6' : '#ffffff';
        input.style.cursor = isBlocked ? 'not-allowed' : 'text';

        if (isBlocked) {
            input.value = IUT_LABEL;
            if(hiddenInput) hiddenInput.value = IUT_COORDS;
            input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
        } else {
            if (value !== null && value !== undefined) input.value = value;
            if (hiddenInput && coords !== null && coords !== undefined) hiddenInput.value = coords;
        }
    }

    function initializeFields() {
        if (departInput && departInput.value.trim() === IUT_LABEL) {
             setFieldState(departInput, coordsDepart, true);
             setFieldState(arriveeInput, coordsArrivee, false, arriveeInput.value, coordsArrivee.value);
        } 
        else if (arriveeInput && arriveeInput.value.trim() === IUT_LABEL) {
             setFieldState(arriveeInput, coordsArrivee, true);
             setFieldState(departInput, coordsDepart, false, departInput.value, coordsDepart.value);
        }
    }

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
                                
                                handleConstraint(input === departInput ? 'depart' : 'arrivee');
                                
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

        document.addEventListener('click', e => {
            if (e.target !== input && e.target !== list) {
                list.classList.add('hidden');
            }
        });
    }

    setupAutocomplete('lieu_depart', 'liste-depart', 'coords_depart');
    setupAutocomplete('lieu_arrivee', 'liste-arrivee', 'coords_arrivee');

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

    if (inverserButton) {
        inverserButton.addEventListener('click', function() {
            isProgrammaticChange = true;
            
            const dVal = departInput.value;
            const dCoords = coordsDepart.value;
            const dReadOnly = departInput.readOnly;

            const aVal = arriveeInput.value;
            const aCoords = coordsArrivee.value;
            const aReadOnly = arriveeInput.readOnly;

            setFieldState(departInput, coordsDepart, aReadOnly, aVal, aCoords);
            setFieldState(arriveeInput, coordsArrivee, dReadOnly, dVal, dCoords);
            
            isProgrammaticChange = false;
            calculateDuration();
        });
    }

    if (useButton && dataContainer) {
        useButton.addEventListener('click', function() {
            try {
                isProgrammaticChange = true;

                const prevDepart = dataContainer.getAttribute('js-depart') || '';
                const prevArrivee = dataContainer.getAttribute('js-arrivee') || '';
                const prevDate = dataContainer.getAttribute('js-date');
                const prevHeure = dataContainer.getAttribute('js-heure'); 
                const prevPlaces = dataContainer.getAttribute('js-places');
                const prevVehicule = dataContainer.getAttribute('js-vehicule');

                const departIsIUT = prevDepart.toLowerCase().includes('iut amiens') || prevDepart.toLowerCase().includes('facultés');
                
                if (departIsIUT) {
                    setFieldState(departInput, coordsDepart, true);
                    setFieldState(arriveeInput, coordsArrivee, false, prevArrivee, ''); 
                } else {
                    setFieldState(arriveeInput, coordsArrivee, true);
                    setFieldState(departInput, coordsDepart, false, prevDepart, '');
                }

                if (prevDate && datePicker) datePicker.setDate(prevDate, true);
                if (prevHeure && timePicker) timePicker.setDate(prevHeure, true);

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
                console.error(error);
            } finally {
                isProgrammaticChange = false;
            }
        });
    }

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
                hiddenDuree.value = json.routes[0].duration; 
            }
        } catch (e) { console.error(e); }
    }

    const savedData = localStorage.getItem('trajet_temp_data');
    if (savedData) {
        try {
            const data = JSON.parse(savedData);
            
            setTimeout(() => {
                isProgrammaticChange = true;

                if (data.lieu_depart && departInput) departInput.value = data.lieu_depart;
                if (data.coords_depart && coordsDepart) coordsDepart.value = data.coords_depart;
                if (data.lieu_arrivee && arriveeInput) arriveeInput.value = data.lieu_arrivee;
                if (data.coords_arrivee && coordsArrivee) coordsArrivee.value = data.coords_arrivee;
                
                if (data.date_depart && datePicker) {
                    datePicker.setDate(data.date_depart, true);
                }
                if (data.heure_depart && timePicker) {
                    timePicker.setDate(data.heure_depart, true);
                }
                
                const places = document.getElementById('places_disponibles');
                if (places && data.places_disponibles) {
                    places.value = data.places_disponibles;
                }

                const prixInput = document.getElementById('prix');
                if (prixInput && data.prix) {
                    prixInput.value = data.prix;
                }

                if(departInput && departInput.value === IUT_LABEL) {
                    setFieldState(departInput, coordsDepart, true);
                    setFieldState(arriveeInput, coordsArrivee, false, data.lieu_arrivee, data.coords_arrivee);
                } else if (arriveeInput && arriveeInput.value === IUT_LABEL) {
                    setFieldState(arriveeInput, coordsArrivee, true);
                    setFieldState(departInput, coordsDepart, false, data.lieu_depart, data.coords_depart);
                }

                isProgrammaticChange = false;
                localStorage.removeItem('trajet_temp_data');
                calculateDuration();

            }, 100);
        } catch(e) { console.error(e); }
    }
    
    initializeFields();
});