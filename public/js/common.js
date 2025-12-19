/*
 |--------------------------------------------------------------------------
 | Storefront Global Utilities
 |--------------------------------------------------------------------------
 | Lightweight helper bundle for behaviors shared across storefront pages.
 | Avoid heavy frameworks: use vanilla JS to keep payload small.
 */

const StorefrontUI = (() => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const flashMessage = (message, type = 'info') => {
        const banner = document.createElement('div');
        banner.className = `sf-flash sf-flash-${type}`;
        banner.textContent = message;
        document.body.appendChild(banner);
        if (!prefersReducedMotion) {
            banner.animate(
                [
                    { transform: 'translateY(-16px)', opacity: 0 },
                    { transform: 'translateY(0)', opacity: 1 },
                ],
                { duration: 220, easing: 'ease-out' },
            );
        }
        setTimeout(() => {
            banner.classList.add('sf-flash-leave');
            setTimeout(() => banner.remove(), 320);
        }, 3200);
    };

    const toggleTheme = () => {
        document.documentElement.classList.toggle('theme-alt');
    };

    return { flashMessage, toggleTheme };
})();

window.StorefrontUI = StorefrontUI;
