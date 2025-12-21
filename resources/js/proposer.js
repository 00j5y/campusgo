// Sauvegarde temporaire du formulaire (retour création véhicule)
window.saveFormData = function () {
    const formData = {
        lieu_depart: document.getElementById('lieu_depart')?.value,
        lieu_arrivee: document.getElementById('lieu_arrivee')?.value,
        date_depart: document.getElementById('date_depart')?.value,
        heure_depart: document.getElementById('heure_depart')?.value,
        places_disponibles: document.getElementById('places_disponibles')?.value
    };
    localStorage.setItem('trajet_temp_data', JSON.stringify(formData));
};

document.addEventListener('DOMContentLoaded', function () {

    // Flatpickr date / heure
    const datePicker = flatpickr("#date_depart", {
        locale: "fr",
        minDate: "today",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        disableMobile: true,
        allowInput: true
    });

    const timePicker = flatpickr("#heure_depart", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true
    });

    const IUT_LABEL = "IUT Amiens, Avenue des Facultés";
    const IUT_COORDS = "2.263592,49.873836";

    const departInput = document.getElementById('lieu_depart');
    const arriveeInput = document.getElementById('lieu_arrivee');
    const coordsDepart = document.getElementById('coords_depart');
    const coordsArrivee = document.getElementById('coords_arrivee');
    const inverserButton = document.getElementById('btn-inverser-lieux');
    const form = document.getElementById('form-creation');

    const useButton = document.getElementById('btn-utiliser');
    const dataContainer = document.getElementById('dernier-trajet-data');

    let isProgrammaticChange = false;

    // Active / désactive un champ et gère la contrainte IUT
    function setFieldState(input, hiddenInput, isBlocked, value = '', coords = '') {
        input.readOnly = isBlocked;
        input.style.backgroundColor = isBlocked ? '#f3f4f6' : '#ffffff';
        input.style.cursor = isBlocked ? 'not-allowed' : 'text';

        if (isBlocked) {
            input.value = IUT_LABEL;
            hiddenInput.value = IUT_COORDS;
            input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
        } else {
            if (value !== null) input.value = value;
            hiddenInput.value = coords || '';
        }
    }

    // Autocomplétion adresse (API Gouv)
    function setupAutocomplete(inputId, listId, hiddenId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        const hidden = document.getElementById(hiddenId);
        let timeout = null;

        if (!input || !list) return;

        input.addEventListener('input', function () {
            if (isProgrammaticChange || input.readOnly) return;
            hidden.value = "";

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
                            li.className = "px-4 py-3 hover:bg-gray-100 cursor-pointer border-b text-sm";
                            li.textContent = f.properties.label;

                            li.addEventListener('click', () => {
                                isProgrammaticChange = true;
                                input.value = f.properties.label;
                                hidden.value = f.geometry.coordinates;
                                list.classList.add('hidden');
                                isProgrammaticChange = false;

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
            if (e.target !== input && e.target !== list) list.classList.add('hidden');
        });
    }

    setupAutocomplete('lieu_depart', 'liste-depart', 'coords_depart');
    setupAutocomplete('lieu_arrivee', 'liste-arrivee', 'coords_arrivee');

    // Contrainte : un seul champ libre, l’autre = IUT
    function handleConstraint(source) {
        if (isProgrammaticChange) return;

        if (source === 'depart') {
            departInput.value.trim() !== ''
                ? setFieldState(arriveeInput, coordsArrivee, true)
                : setFieldState(arriveeInput, coordsArrivee, false, '');
        } else {
            arriveeInput.value.trim() !== ''
                ? setFieldState(departInput, coordsDepart, true)
                : setFieldState(departInput, coordsDepart, false, '');
        }
    }

    departInput.addEventListener('input', () => handleConstraint('depart'));
    arriveeInput.addEventListener('input', () => handleConstraint('arrivee'));

    // Inversion départ / arrivée
    if (inverserButton) {
        inverserButton.addEventListener('click', () => {
            isProgrammaticChange = true;

            const dVal = departInput.value;
            const dCoords = coordsDepart.value;
            const dRO = departInput.readOnly;

            const aVal = arriveeInput.value;
            const aCoords = coordsArrivee.value;
            const aRO = arriveeInput.readOnly;

            setFieldState(departInput, coordsDepart, aRO, aVal, aCoords);
            setFieldState(arriveeInput, coordsArrivee, dRO, dVal, dCoords);

            isProgrammaticChange = false;
        });
    }

    // Réutilisation du dernier trajet
    if (useButton && dataContainer) {
        useButton.addEventListener('click', () => {
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
                setFieldState(arriveeInput, coordsArrivee, false, prevArrivee);
            } else {
                setFieldState(arriveeInput, coordsArrivee, true);
                setFieldState(departInput, coordsDepart, false, prevDepart);
            }

            if (prevDate && datePicker) datePicker.setDate(prevDate, true);
            if (prevHeure && timePicker) timePicker.setDate(prevHeure, true);

            document.getElementById('places_disponibles')?.setAttribute('value', prevPlaces);
            document.getElementById('vehicule_id')?.setAttribute('value', prevVehicule);

            [departInput, arriveeInput].forEach(input => {
                input.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                const errId = input === departInput ? 'error-lieu-depart' : 'error-lieu-arrivee';
                document.getElementById(errId)?.classList.add('hidden');
            });

            isProgrammaticChange = false;
        });
    }

    // Validation avant submit
    if (form) {
        form.addEventListener('submit', e => {
            let valid = true;

            if (departInput.value && !coordsDepart.value && departInput.value !== IUT_LABEL) {
                valid = false;
                departInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                document.getElementById('error-lieu-depart')?.classList.remove('hidden');
            }

            if (arriveeInput.value && !coordsArrivee.value && arriveeInput.value !== IUT_LABEL) {
                valid = false;
                arriveeInput.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                document.getElementById('error-lieu-arrivee')?.classList.remove('hidden');
            }

            if (!valid) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    }

    // Restauration après retour création véhicule
    const savedData = localStorage.getItem('trajet_temp_data');
    if (savedData) {
        const data = JSON.parse(savedData);

        setTimeout(() => {
            isProgrammaticChange = true;

            if (data.lieu_depart) departInput.value = data.lieu_depart;
            if (data.lieu_arrivee) arriveeInput.value = data.lieu_arrivee;
            if (data.date_depart && datePicker) datePicker.setDate(data.date_depart);
            if (data.heure_depart && timePicker) timePicker.setDate(data.heure_depart);
            if (data.places_disponibles) document.getElementById('places_disponibles').value = data.places_disponibles;

            if (departInput.value === IUT_LABEL) {
                setFieldState(departInput, coordsDepart, true);
                setFieldState(arriveeInput, coordsArrivee, false, data.lieu_arrivee);
            } else if (arriveeInput.value === IUT_LABEL) {
                setFieldState(arriveeInput, coordsArrivee, true);
                setFieldState(departInput, coordsDepart, false, data.lieu_depart);
            }

            isProgrammaticChange = false;
            localStorage.removeItem('trajet_temp_data');
        }, 100);
    }
});
