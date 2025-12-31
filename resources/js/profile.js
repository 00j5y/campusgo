
document.addEventListener('DOMContentLoaded', function () {
    
    const photoInput = document.getElementById('photo_input');
    const previewImage = document.getElementById('preview_image');

    if (photoInput && previewImage) {
        photoInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            
            if (file) {
                previewImage.src = URL.createObjectURL(file);
                
                previewImage.classList.remove('w-10', 'h-10', 'object-contain');
                previewImage.classList.add('w-full', 'h-full', 'object-cover');
                
                previewImage.onload = function() {
                    URL.revokeObjectURL(previewImage.src);
                }
            }
        });
    }

    const deleteBtn = document.getElementById('btn-delete-photo');
    const deleteForm = document.getElementById('form-delete-photo');

    if (deleteBtn && deleteForm) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('Voulez-vous vraiment supprimer votre photo ?')) {
                deleteForm.submit();
            }
        });
    }
});