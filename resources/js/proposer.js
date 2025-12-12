//public/js/proposer.js

document.addEventListener('DOMContentLoaded', function() {
    const iutAddress = "IUT Amiens, Avenue des Facultés";
    const departInput = document.getElementById('lieu_depart');
    const arriveeInput = document.getElementById('lieu_arrivee');
    const inverserButton = document.getElementById('btn-inverser-lieux');
    
    let isProgrammaticChange = false;
    
    //Fonction utilitaire pour bloquer/débloquer et gérer la valeur IUT
    function setInputState(input, isIUTBlocked, valueToSet) {
        input.readOnly = isIUTBlocked;
        input.style.backgroundColor = isIUTBlocked ? '#f0f0f0' : '';
        
        //Si c'est le champ IUT (bloqué), forcer la valeur IUT
        if (isIUTBlocked) {
            input.value = iutAddress;
        } else {
            //Si ce n'est pas bloqué, utiliser la valeur fournie (saisie utilisateur)
            input.value = valueToSet || ''; 
        }
    }
    
    //Initialisation de l'état : Les deux champs sont vides et modifiables
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
            
            //L'utilisateur tape dans le champ (Départ ou Arrivée)
            //L'autre champ est OBLIGATOIREMENT l'IUT et bloqué
            setInputState(otherInput, true); 
            
            //Le champ modifié est débloqué et conserve sa saisie
            setInputState(changedInput, false, changedInput.value); 
            
        } else {

            //L'utilisateur EFFACE complétement le champ (vide)
            //Les deux champs sont maintenant vides et modifiables
            setInputState(changedInput, false, '');
            setInputState(otherInput, false, ''); 
        }
        
        isProgrammaticChange = false;
    }

    //Inversion du départ et d'arrivée

    function invertLieux() {
        
        //Lire les valeurs actuelles
        const departValue = departInput.value;
        const arriveeValue = arriveeInput.value;
        
        const departEstBloque = departInput.readOnly;
        const arriveeEstBloque = arriveeInput.readOnly;
        
        isProgrammaticChange = true;
        
        if (departEstBloque) {

            //IUT était en DÉPART : Nouvelle état: Adresse -> IUT
            //DÉPART : Débloqué et reçoit l'ancienne valeur d'ARRIVÉE
            setInputState(departInput, false, arriveeValue); 

            //ARRIVÉE : Bloqué et reçoit l'IUT
            setInputState(arriveeInput, true); 

        } else if (arriveeEstBloque) {

            //IUT était en ARRIVÉE : Nouvelle état: IUT -> Adresse
            //ARRIVÉE : Débloqué, reçoit l'ancienne valeur de DÉPART (la Adresse)
            setInputState(arriveeInput, false, departValue);

            //DÉPART : Bloqué, reçoit l'IUT
            setInputState(departInput, true);
            
        } else {

            //Les deux sont vides/modifiables (état initial).
            //On force l'état par défaut : Adresse en Départ / IUT en Arrivée
            //DÉPART : Débloqué
            setInputState(departInput, false, departValue); 
            
            //ARRIVÉE : Bloqué (IUT)
            setInputState(arriveeInput, true); 
        }

        isProgrammaticChange = false;
    }

    //Écouteurs d'événements et Initialisation 
    if (departInput && arriveeInput) {
        departInput.addEventListener('input', handleInput);
        arriveeInput.addEventListener('input', handleInput);
        
        initializeFields(); 
    }

    if (inverserButton) {
        inverserButton.addEventListener('click', invertLieux);
    }

    //Bouton "Utiliser"

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

            //On active le flag pour que le script ignore les changements de valeur
            isProgrammaticChange = true;
            
            //Déterminer quel champ était l'IUT dans le trajet précédent
            const dernierDepartEstIUT = depart.toLowerCase() === iutAddress.toLowerCase();
            const dernierArriveeEstIUT = arrivee.toLowerCase() === iutAddress.toLowerCase();
            
            //Application de la Contrainte IUT et remplissage des champs de Lieu
            if (dernierDepartEstIUT) {
                //Trajet précédent: IUT -> Adresse
                setInputState(departInput, true, depart); //Départ Bloqué sur IUT
                setInputState(arriveeInput, false, arrivee); //Arrivée: Débloqué et reçoit l'adresse de la Adresse
                
            } else if (dernierArriveeEstIUT) {
                //Trajet précédent: Adresse -> IUT
                setInputState(arriveeInput, true, arrivee); //Arrivée Bloqué sur IUT
                setInputState(departInput, false, depart); //Départ Débloqué et reçoit l'adresse de la Adresse
                
            } else {
                //Cas d'erreur : On force la contrainte par défaut (IUT en Arrivée)
                
                //On met les valeurs brutes
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