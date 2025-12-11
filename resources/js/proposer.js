// public/js/proposer.js

document.addEventListener('DOMContentLoaded', function() {
    const iutAddress = "IUT Amiens, Avenue des Facultés";
    const departInput = document.getElementById('lieu_depart');
    const arriveeInput = document.getElementById('lieu_arrivee');
    
    let isProgrammaticChange = false;
    
    // Fonction utilitaire pour bloquer/débloquer et gérer la valeur IUT
    function setInputState(input, isIUTBlocked, valueToSet) {
        input.readOnly = isIUTBlocked;
        input.style.backgroundColor = isIUTBlocked ? '#f0f0f0' : '';
        
        // Si c'est le champ IUT (bloqué), forcer la valeur IUT
        if (isIUTBlocked) {
            input.value = iutAddress;
        } else {
            // Si ce n'est pas bloqué, laisser vide
            input.value = valueToSet || ''; 
        }
    }
    
    // Initialisation de l'état : Les deux champs sont vides et modifiables
    function initializeFields() {
        if (arriveeInput) {
            setInputState(arriveeInput, false, ''); 
        }
        if (departInput) {
            setInputState(departInput, false, '');
        }
    }
    
    //Logique d'Auto-Remplissage et d'Interversion 

    function handleInput(event) {
        if (isProgrammaticChange) return;

        const changedInput = event.target;
        let otherInput;
        
        if (changedInput === departInput) {
            otherInput = arriveeInput;
        } else if (changedInput === arriveeInput) {
            otherInput = departInput;
        } else {
            return;
        }

        const isInputEmpty = changedInput.value.trim() === '';

        isProgrammaticChange = true;
        
        if (!isInputEmpty) {
            
            // CAS 1 : L'utilisateur TAPE dans le champ (DÉPART)
            // L'autre champ (ARRIVÉE) est OBLIGATOIREMENT l'IUT et bloqué
            setInputState(otherInput, true); 
            
            // Le champ modifié est débloqué (si ce n'était pas déjà le cas)
            setInputState(changedInput, false, changedInput.value); 
            
        } else {

            // CAS 2 : L'utilisateur EFFACE complétement le champ (vide)
            // Les deux champs sont maintenant vides et modifiables
            setInputState(changedInput, false, '');
            setInputState(otherInput, false, ''); 
        }
        
        isProgrammaticChange = false;
    }

    // Écouteurs d'événements et Initialisation 
    if (departInput && arriveeInput) {
        departInput.addEventListener('input', handleInput);
        arriveeInput.addEventListener('input', handleInput);
        
        initializeFields(); 
    }

    // ---------------- Bouton "Utiliser" ----------------------

    const useButton = document.getElementById('btn-utiliser'); 
    const dataContainer = document.getElementById('dernier-trajet-data'); 

    if (useButton && dataContainer) {
        useButton.addEventListener('click', function() {
            
            //Récupération des données du dernier trajet (via les attributs de données du Blade)
            const depart = dataContainer.getAttribute('js-depart');
            const arrivee = dataContainer.getAttribute('js-arrivee');
            const heure = dataContainer.getAttribute('js-heure');
            const places = dataContainer.getAttribute('js-places');
            const vehiculeId = dataContainer.getAttribute('js-vehicule');

            // On active le flag pour que le script ignore les changements de valeur
            isProgrammaticChange = true;
            
            //Déterminer quel champ était l'IUT dans le trajet précédent
            const dernierDepartEstIUT = depart.toLowerCase() === iutAddress.toLowerCase();
            const dernierArriveeEstIUT = arrivee.toLowerCase() === iutAddress.toLowerCase();
            
            //Application de la Contrainte IUT et remplissage des champs de Lieu
            if (dernierDepartEstIUT) {
                // Trajet précédent: Le DEPART doit être IUT qui est bloqué
                // Départ Bloqué sur IUT
                setInputState(departInput, true, depart); 
                
                //Arrivée: Débloqué et reçoit l'adresse de la ville
                setInputState(arriveeInput, false, arrivee); 
                
            } else if (dernierArriveeEstIUT) {
                //Trajet précédent: L'ARRIVÉE doit être IUT qui est bloquée
                
                //Arrivée Bloqué sur IUT
                setInputState(arriveeInput, true, arrivee); 
                
                //Départ Débloqué et reçoit l'adresse de la ville
                setInputState(departInput, false, depart); 
                
            } else {
                //Cas d'erreur ou cas où l'IUT n'était ni en départ ni en arrivée (doit être géré par la validation)
                departInput.value = depart; 
                arriveeInput.value = arrivee;
                
                //On applique la contrainte manuellement (force l'arrivée à l'IUT)
                setInputState(arriveeInput, true, arrivee); 
                setInputState(departInput, false, depart);
            }

            //Remplissage des autres champs (Heure, Places, Véhicule)
            document.getElementById('heure_depart').value = heure;
            document.getElementById('places_disponibles').value = places;
            const selectVehicule = document.getElementById('vehicule_id');
            if (selectVehicule) {
                selectVehicule.value = vehiculeId;
            }

            isProgrammaticChange = false;
        });
    }

});