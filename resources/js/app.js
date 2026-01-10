import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const compteurs = document.querySelectorAll('.js-counter');

    compteurs.forEach(compteur => {
        const nombreFinal = parseInt(compteur.getAttribute('data-target'));
        const dureeAnimation = 1000;
        let tempsDeDepart = null;

        function effetRalentissement(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        function animer(tempsActuel) {
            if (!tempsDeDepart) tempsDeDepart = tempsActuel;

            const tempsEcoule = tempsActuel - tempsDeDepart;
            const avancement = Math.min(tempsEcoule / dureeAnimation, 1);
            const progressionLisse = effetRalentissement(avancement);
            const nombreActuel = Math.floor(progressionLisse * nombreFinal);

            compteur.textContent = nombreActuel;

            if (avancement < 1) {
                window.requestAnimationFrame(animer);
            } else {
                compteur.textContent = nombreFinal;
            }
        }
        window.requestAnimationFrame(animer);
    });
});


window.openModal = function(modalId, actionUrl = null) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    if (actionUrl) {
        const form = modal.querySelector('form');
        if (form) form.action = actionUrl;
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    modal.classList.add('hidden');
    document.body.style.overflow = '';
};