// User Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('user-sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    // Mobile sidebar toggle
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('opacity-0');
            sidebarOverlay.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        });
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.remove('opacity-100');
            sidebarOverlay.classList.add('opacity-0');
            document.body.style.overflow = '';
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.remove('opacity-100');
            sidebarOverlay.classList.add('opacity-0');
            document.body.style.overflow = '';
        });
    }

    // Submenu toggle
    const menuToggles = document.querySelectorAll('.menu-toggle');
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const menuIndex = this.dataset.menu;
            const submenu = document.querySelector(`[data-submenu="${menuIndex}"]`);
            const isOpen = submenu.dataset.open === 'true';

            if (isOpen) {
                submenu.classList.remove('max-h-96', 'opacity-100');
                submenu.classList.add('max-h-0', 'opacity-0');
                submenu.dataset.open = 'false';
                this.querySelector('svg').classList.remove('rotate-90');
            } else {
                submenu.classList.remove('max-h-0', 'opacity-0');
                submenu.classList.add('max-h-96', 'opacity-100');
                submenu.dataset.open = 'true';
                this.querySelector('svg').classList.add('rotate-90');
            }
        });
    });

    // Theme toggle (if needed)
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('storefront-user-theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('storefront-user-theme', 'dark');
            }
        });
    }
});
