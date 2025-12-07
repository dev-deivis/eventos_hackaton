// Dark Mode Toggle Script
document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Obtener tema guardado o usar preferencia del sistema
    const getTheme = () => {
        const storedTheme = localStorage.getItem('color-theme');
        if (storedTheme) {
            return storedTheme;
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    // Aplicar tema
    const applyTheme = (theme) => {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            themeToggleLightIcon.classList.add('hidden');
            themeToggleDarkIcon.classList.remove('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            themeToggleDarkIcon.classList.add('hidden');
            themeToggleLightIcon.classList.remove('hidden');
        }
    };

    // Aplicar tema inicial
    const currentTheme = getTheme();
    applyTheme(currentTheme);

    // Toggle al hacer clic
    themeToggleBtn.addEventListener('click', function() {
        const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        // Guardar preferencia
        localStorage.setItem('color-theme', newTheme);
        
        // Aplicar nuevo tema
        applyTheme(newTheme);
    });
});
