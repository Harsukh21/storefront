// Admin Panel JavaScript

// Theme Toggle
window.AdminTheme = {
    toggle() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('storefront-admin-theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('storefront-admin-theme', 'dark');
        }
    }
};

// Sidebar Toggle (Mobile)
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('admin-sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarClose = document.getElementById('sidebar-close');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    // Open sidebar
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('opacity-0', 'pointer-events-none');
            sidebarOverlay.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close sidebar
    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebarOverlay.classList.remove('opacity-100');
        sidebarOverlay.classList.add('opacity-0', 'pointer-events-none');
        document.body.style.overflow = '';
    }

    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Menu Toggle (Submenu)
    const menuToggles = document.querySelectorAll('.menu-toggle');
    
    menuToggles.forEach((toggle) => {
        const menuIndex = toggle.getAttribute('data-menu');
        const submenu = document.querySelector(`[data-submenu="${menuIndex}"]`);
        const icon = toggle.querySelector('svg:last-child');
        
        if (!submenu) {
            console.warn('Submenu not found for menu index:', menuIndex);
            return;
        }
        
        // Check initial state from classes
        const isInitiallyOpen = submenu.classList.contains('max-h-96');
        
        // Set initial state using data attribute for reliable state tracking
        if (isInitiallyOpen) {
            submenu.setAttribute('data-open', 'true');
            if (icon) {
                icon.classList.add('rotate-90');
            }
        } else {
            submenu.setAttribute('data-open', 'false');
            if (icon) {
                icon.classList.remove('rotate-90');
            }
        }
        
        // Toggle on click
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const isOpen = submenu.getAttribute('data-open') === 'true';
            
            if (isOpen) {
                // Close menu
                submenu.classList.remove('max-h-96', 'opacity-100');
                submenu.classList.add('max-h-0', 'opacity-0');
                submenu.setAttribute('data-open', 'false');
                if (icon) {
                    icon.classList.remove('rotate-90');
                }
            } else {
                // Open menu
                submenu.classList.remove('max-h-0', 'opacity-0');
                submenu.classList.add('max-h-96', 'opacity-100');
                submenu.setAttribute('data-open', 'true');
                if (icon) {
                    icon.classList.add('rotate-90');
                }
            }
        });
    });

    // Close sidebar on window resize (if desktop)
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) {
            closeSidebar();
        }
    });
});

// Toast notifications will be handled by the toast.js file
