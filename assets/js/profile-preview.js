document.addEventListener('DOMContentLoaded', () => {
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];

            if (file) {
                // Usamos FileReader para leer el archivo y mostrarlo como una URL de datos
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Cuando el archivo se ha leído, actualizamos el 'src' de la imagen
                    avatarPreview.src = e.target.result;
                }

                reader.readAsDataURL(file);
            }
        });
    }
});
