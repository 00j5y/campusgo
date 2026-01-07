document.addEventListener('DOMContentLoaded', function () {
    
    const ID_INPUT_FILE   = 'input_photo_upload';
    const ID_PREVIEW_IMG  = 'img_photo_preview';
    const ID_BTN_DELETE   = 'btn_photo_delete';
    const ID_INPUT_DELETE = 'input_delete_photo';
    const ID_INITIALS     = 'initials_placeholder';

    const photoInput = document.getElementById(ID_INPUT_FILE);
    const previewImage = document.getElementById(ID_PREVIEW_IMG);
    const deleteBtn = document.getElementById(ID_BTN_DELETE);
    const deleteInput = document.getElementById(ID_INPUT_DELETE);
    const initialsDiv = document.getElementById(ID_INITIALS);

    // --- LOGIQUE D'UPLOAD ---
    if (photoInput && previewImage) {
        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    
                    previewImage.classList.remove('hidden');
                    previewImage.classList.add('block');
                    
                    if (initialsDiv) {
                        initialsDiv.classList.add('hidden');
                        initialsDiv.classList.remove('flex');
                    }
                    
                    if(deleteInput) deleteInput.value = "0";
                    
                    if(deleteBtn) deleteBtn.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // --- LOGIQUE DE SUPPRESSION ---
    if (deleteBtn && deleteInput) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('La photo sera supprimée lorsque vous cliquerez sur "Enregistrer les modifications". Continuer ?')) {
                
                deleteInput.value = "1";

                if(photoInput) photoInput.value = "";

                if(previewImage) {
                    previewImage.classList.add('hidden');
                    previewImage.classList.remove('block');
                    previewImage.src = '#';
                }

                if (initialsDiv) {
                    initialsDiv.classList.remove('hidden');
                    initialsDiv.classList.add('flex');
                }

                deleteBtn.classList.add('hidden');
            }
        });
    }
});