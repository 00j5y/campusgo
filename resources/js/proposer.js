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
});