document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    // Función para aplicar el tema
    const applyTheme = (theme) => {
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            darkIcon.style.display = 'none';
            lightIcon.style.display = 'block';
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.removeAttribute('data-theme');
            darkIcon.style.display = 'block';
            lightIcon.style.display = 'none';
            localStorage.setItem('theme', 'light');
        }
    };

    // Obtener preferencia guardada o del sistema
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme) {
        applyTheme(savedTheme);
    } else {
        applyTheme(prefersDark ? 'dark' : 'light');
    }

    // Event listener para el botón
    themeToggleBtn.addEventListener('click', () => {
        const currentTheme = document.documentElement.hasAttribute('data-theme') ? 'dark' : 'light';
        applyTheme(currentTheme === 'dark' ? 'light' : 'dark');
    });
});
