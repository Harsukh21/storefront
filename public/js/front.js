(function () {
    const storageKey = 'storefront-theme';
    const root = document.documentElement;
    const clampTheme = (value) => (value === 'light' || value === 'dark') ? value : 'dark';

    const applyTheme = (theme) => {
        root.classList.toggle('dark', theme === 'dark');
        document.querySelectorAll('[data-theme-label]').forEach((el) => {
            el.textContent = theme === 'dark' ? 'Dark' : 'Light';
        });
    };

    const systemPrefersDark = () => window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const storedTheme = clampTheme(localStorage.getItem(storageKey));
    const initialTheme = storedTheme || (systemPrefersDark() ? 'dark' : 'light');

    applyTheme(initialTheme);

    // Circular reveal animation - matching sitestash.org
    // Content never disappears - overlay creates visual effect without hiding content
    const createCircularReveal = (event, targetTheme) => {
        const button = event?.target?.closest('.theme-toggle') || event?.target;
        if (!button) return false;

        // Get button position
        const rect = button.getBoundingClientRect();
        const x = rect.left + rect.width / 2;
        const y = rect.top + rect.height / 2;
        
        // Calculate maximum distance to cover entire screen
        const maxDistance = Math.max(
            Math.hypot(x, y),
            Math.hypot(window.innerWidth - x, y),
            Math.hypot(x, window.innerHeight - y),
            Math.hypot(window.innerWidth - x, window.innerHeight - y)
        );

        // CRITICAL: Apply theme FIRST synchronously - content transitions immediately, never disappears
        localStorage.setItem(storageKey, targetTheme);
        applyTheme(targetTheme);

        // Create overlay immediately - uses mix-blend-mode: difference so content never disappears
        // The overlay creates the circular reveal visual effect without hiding/blocking content
        const overlay = document.createElement('div');
        overlay.className = 'theme-transition-overlay';
        overlay.style.cssText = `
            clip-path: circle(0% at ${x}px ${y}px);
        `;
        
        document.body.appendChild(overlay);
        
        // Trigger animation immediately on next frame
        // Content is already transitioning smoothly underneath - overlay just creates visual effect
        requestAnimationFrame(() => {
            overlay.style.clipPath = `circle(${maxDistance * 1.2}px at ${x}px ${y}px)`;
        });

        // Remove overlay after animation completes
        setTimeout(() => {
            overlay.style.opacity = '0';
            overlay.style.transition = 'opacity 0.15s ease-out';
            setTimeout(() => {
                overlay.remove();
            }, 150);
        }, 600);

        return true;
    };

    window.StorefrontTheme = {
        toggle(event) {
            const current = root.classList.contains('dark') ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            
            // Create circular reveal animation
            const hasTransition = createCircularReveal(event, next);
            
            if (!hasTransition) {
                // Fallback without animation
                localStorage.setItem(storageKey, next);
                applyTheme(next);
            }
        },
        set(theme) {
            const next = clampTheme(theme);
            localStorage.setItem(storageKey, next);
            applyTheme(next);
        },
    };

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
        if (!localStorage.getItem(storageKey)) {
            applyTheme(event.matches ? 'dark' : 'light');
        }
    });

    const mobileMenu = document.querySelector('[data-front-mobile-menu]');
    const mobileToggles = document.querySelectorAll('[data-front-menu-toggle]');
    const closeMenuOnLinks = mobileMenu ? mobileMenu.querySelectorAll('a') : [];
    let menuOpen = false;

    const setMenuState = (open) => {
        if (!mobileMenu) {
            return;
        }
        menuOpen = open;
        if (open) {
            mobileMenu.classList.add('front-mobile-open');
            mobileMenu.classList.remove('hidden');
        } else {
            mobileMenu.classList.remove('front-mobile-open');
            mobileMenu.classList.add('hidden');
        }

        mobileToggles.forEach((toggle) => {
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    };

    mobileToggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            setMenuState(!menuOpen);
        });
    });

    closeMenuOnLinks.forEach((link) => {
        link.addEventListener('click', () => setMenuState(false));
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            setMenuState(false);
        }
    });

    // Update cart count on page load
    const updateCartCount = async () => {
        try {
            const cartRoute = document.body.dataset.cartRoute || '/cart/mini';
            const response = await fetch(cartRoute, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });
            if (response.ok) {
                const data = await response.json();
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) {
                    if (data.item_count > 0) {
                        cartCountEl.textContent = data.item_count;
                        cartCountEl.classList.remove('hidden');
                    } else {
                        cartCountEl.classList.add('hidden');
                    }
                }
            }
        } catch (error) {
            console.warn('Failed to load cart count', error);
        }
    };

    if (document.getElementById('cart-count')) {
        updateCartCount();
        // Update after cart actions
        document.addEventListener('cartUpdated', updateCartCount);
    }
})();
