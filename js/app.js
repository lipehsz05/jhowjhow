// JavaScript principal para o sistema

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Toggle do menu lateral em dispositivos móveis
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            
            if (sidebarOverlay) {
                sidebarOverlay.classList.toggle('hidden');
            }
        });
    }
    
    // Fechar o sidebar quando clicar no overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    }
    
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
    
    // Toggle de visualização de senha
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Alternar ícone
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    }
});
