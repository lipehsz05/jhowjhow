// Importando o arquivo CSS para que o Vite o processe
import '../css/app.css';

// Aqui você pode adicionar qualquer JavaScript personalizado para o seu aplicativo

// Inicializar os dropdowns interativos
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown toggle
    const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-dropdown-toggle');
            const target = document.getElementById(targetId);
            
            if (target) {
                target.classList.toggle('hidden');
            }
        });
    });
    
    // Fechar os dropdowns quando clicar fora
    document.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll('.dropdown-menu:not(.hidden)');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target) && 
                !e.target.hasAttribute('data-dropdown-toggle')) {
                dropdown.classList.add('hidden');
            }
        });
    });
});
