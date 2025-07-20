document.addEventListener('DOMContentLoaded', () => {
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenuDropdown = document.getElementById('user-menu-dropdown');

    if (userMenuButton && userMenuDropdown) {
        // Toggle (abrir/cerrar) el menú al hacer clic en el botón
        userMenuButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Evita que el clic se propague al documento
            userMenuDropdown.classList.toggle('active');
        });

        // Cerrar el menú si se hace clic fuera de él
        document.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                userMenuDropdown.classList.remove('active');
            }
        });
    }
});
