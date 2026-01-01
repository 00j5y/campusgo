document.addEventListener('DOMContentLoaded', function () {
    
    const ID_INPUT_FILE   = 'input_photo_upload';
    const ID_PREVIEW_IMG  = 'img_photo_preview';
    const ID_BTN_DELETE   = 'btn_photo_delete';
    const ID_INPUT_DELETE = 'input_delete_photo'; // Le flag caché

    const photoInput = document.getElementById(ID_INPUT_FILE);
    const previewImage = document.getElementById(ID_PREVIEW_IMG);
    const deleteBtn = document.getElementById(ID_BTN_DELETE);
    const deleteInput = document.getElementById(ID_INPUT_DELETE);

    if (photoInput && previewImage) {
        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('w-10', 'h-10', 'object-contain');
                    previewImage.classList.add('w-full', 'h-full', 'object-cover');
                    
                    // Si on upload une nouvelle photo, on annule la suppression
                    if(deleteInput) deleteInput.value = "0";
                    // On réaffiche le bouton de suppression
                    if(deleteBtn) deleteBtn.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    if (deleteBtn && deleteInput && previewImage) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('La photo sera supprimée lorsque vous cliquerez sur "Enregistrer". Continuer ?')) {
                
                // on marque la photo comme "à supprimer" pour le backend
                deleteInput.value = "1";

                // on vide l'input file (si l'utilisateur avait uploadé une nouvelle photo avant de cliquer sur supprimer)
                photoInput.value = "";

                // on remet l'image par défaut
                const defaultSrc = previewImage.getAttribute('data-default');
                previewImage.src = defaultSrc;
                previewImage.classList.remove('w-full', 'h-full', 'object-cover');
                previewImage.classList.add('w-10', 'h-10', 'object-contain');
                deleteBtn.classList.add('hidden');
            }
        });
    }
});