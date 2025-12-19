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

    window.StorefrontTheme = {
        toggle() {
            const current = root.classList.contains('dark') ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            localStorage.setItem(storageKey, next);
            applyTheme(next);
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
