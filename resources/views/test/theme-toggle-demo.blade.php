<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Theme Toggle Animation Demo - {{ config('app.name') }}</title>

    <script>
        try {
            const key = 'theme-toggle-demo-theme';
            const stored = localStorage.getItem(key);
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        } catch (error) {
            console.warn('Unable to read theme preference', error);
        }
    </script>

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'sf-night': {
                            900: '#060b1b',
                            800: '#101a33',
                            700: '#162246',
                        },
                        'sf-accent': {
                            primary: '#29ffc6',
                            secondary: '#7c5cff',
                            contrast: '#ff7cf6',
                        },
                        'sf-surface': '#eef2ff',
                        'sf-border': '#d6dcff',
                    },
                    boxShadow: {
                        'sf-glow': '0 25px 70px -45px rgba(10, 22, 55, 0.45)',
                    },
                },
            },
        };
    </script>

    <style>
        /* Smooth transitions for theme changes - matching sitestash.org */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        /* Circular Reveal Animation - Exact match to sitestash.org */
        /* Content never disappears - overlay creates visual effect without hiding content */
        .theme-transition-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 999999;
            pointer-events: none;
            clip-path: circle(0% at 50% 50%);
            transition: clip-path 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            /* Use mix-blend-mode so overlay doesn't hide content - creates reveal effect */
            mix-blend-mode: difference;
            background: #ffffff;
        }

        /* Fade Animation */
        .theme-fade-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 999999;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.4s ease-in-out;
        }

        .theme-fade-overlay.active {
            opacity: 1;
        }

        /* Slide Animation */
        .theme-slide-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 999999;
            pointer-events: none;
            transform: translateX(-100%);
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .theme-slide-overlay.active {
            transform: translateX(0);
        }

        /* Morphing Animation */
        .theme-morph-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 999999;
            pointer-events: none;
            clip-path: circle(0% at 50% 50%);
            transition: clip-path 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .theme-morph-overlay.active {
            clip-path: circle(150% at 50% 50%);
        }

        /* Toggle Button - Matching sitestash.org style */
        .theme-toggle-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            transition: background-color 0.2s ease;
        }

        .theme-toggle-btn:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .dark .theme-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .theme-toggle-icon {
            width: 20px;
            height: 20px;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .theme-toggle-icon.sun {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }

        .dark .theme-toggle-icon.sun {
            opacity: 0;
            transform: scale(0) rotate(90deg);
        }

        .theme-toggle-icon.moon {
            position: absolute;
            opacity: 0;
            transform: scale(0) rotate(-90deg);
        }

        .dark .theme-toggle-icon.moon {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    </style>
</head>
<body class="min-h-screen bg-white text-slate-900 dark:bg-sf-night-900 dark:text-slate-100">
    <div class="container mx-auto px-6 py-12">
        <!-- Header Section -->
        <header class="mb-12 flex items-center justify-between border-b border-slate-200 pb-6 dark:border-slate-700">
            <div>
                <h1 class="mb-2 text-4xl font-bold text-slate-900 dark:text-sf-accent-primary">
                    Theme Toggle Demo
                </h1>
                <p class="text-slate-600 dark:text-slate-400">
                    Multiple animation styles - Circular reveal matches sitestash.org
                </p>
            </div>
            <button 
                id="theme-toggle" 
                class="theme-toggle-btn"
                onclick="toggleTheme(event)"
                aria-label="Toggle theme">
                <svg class="theme-toggle-icon sun" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <svg class="theme-toggle-icon moon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
        </header>

        <!-- Animation Selection -->
        <div class="mb-12 rounded-2xl border border-slate-200 bg-white p-8 shadow-lg dark:border-slate-700 dark:bg-sf-night-800">
            <h2 class="mb-6 text-2xl font-semibold text-slate-900 dark:text-slate-100">
                Choose Animation Style
            </h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <button 
                    onclick="setAnimationType('circular')" 
                    class="animation-option rounded-lg border-2 border-slate-300 bg-slate-50 p-4 text-left transition hover:border-sf-accent-primary hover:bg-sf-accent-primary/10 dark:border-slate-600 dark:bg-sf-night-900 dark:hover:border-sf-accent-primary"
                    data-type="circular">
                    <div class="mb-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Circular Reveal</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">Expands from button (sitestash.org style)</div>
                </button>
                <button 
                    onclick="setAnimationType('fade')" 
                    class="animation-option rounded-lg border-2 border-slate-300 bg-slate-50 p-4 text-left transition hover:border-sf-accent-primary hover:bg-sf-accent-primary/10 dark:border-slate-600 dark:bg-sf-night-900 dark:hover:border-sf-accent-primary"
                    data-type="fade">
                    <div class="mb-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Fade Transition</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">Smooth fade in/out</div>
                </button>
                <button 
                    onclick="setAnimationType('slide')" 
                    class="animation-option rounded-lg border-2 border-slate-300 bg-slate-50 p-4 text-left transition hover:border-sf-accent-primary hover:bg-sf-accent-primary/10 dark:border-slate-600 dark:bg-sf-night-900 dark:hover:border-sf-accent-primary"
                    data-type="slide">
                    <div class="mb-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Slide Transition</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">Slides from left to right</div>
                </button>
                <button 
                    onclick="setAnimationType('morph')" 
                    class="animation-option rounded-lg border-2 border-slate-300 bg-slate-50 p-4 text-left transition hover:border-sf-accent-primary hover:bg-sf-accent-primary/10 dark:border-slate-600 dark:bg-sf-night-900 dark:hover:border-sf-accent-primary"
                    data-type="morph">
                    <div class="mb-2 text-lg font-semibold text-slate-900 dark:text-slate-100">Morph Transition</div>
                    <div class="text-sm text-slate-600 dark:text-slate-400">Circular morph from center</div>
                </button>
            </div>
            <div class="mt-4 text-sm text-slate-600 dark:text-slate-400">
                <strong>Current:</strong> <span id="current-animation" class="font-semibold text-sf-accent-primary">Circular Reveal</span>
            </div>
        </div>

        <!-- Sample Content Cards -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-md dark:border-slate-700 dark:bg-sf-night-800">
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-slate-100">Card 1</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Watch how the content stays visible during the animation. The overlay reveals the new theme smoothly without hiding content.
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-md dark:border-slate-700 dark:bg-sf-night-800">
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-slate-100">Card 2</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    The animation creates a smooth, engaging transition that feels natural and polished. Content transitions smoothly.
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-md dark:border-slate-700 dark:bg-sf-night-800">
                <h3 class="mb-3 text-xl font-semibold text-slate-900 dark:text-slate-100">Card 3</h3>
                <p class="text-slate-600 dark:text-slate-400">
                    Try clicking the toggle multiple times to see the effect. Notice how content never disappears - it transitions smoothly.
                </p>
            </div>
        </div>
    </div>

    <script>
        const storageKey = 'theme-toggle-demo-theme';
        const animationKey = 'theme-toggle-demo-animation';
        const root = document.documentElement;
        let currentAnimationType = localStorage.getItem(animationKey) || 'circular';

        // Initialize animation type
        function setAnimationType(type) {
            currentAnimationType = type;
            localStorage.setItem(animationKey, type);
            
            // Update UI
            document.querySelectorAll('.animation-option').forEach(btn => {
                btn.classList.remove('border-sf-accent-primary', 'bg-sf-accent-primary/20');
            });
            const selectedBtn = document.querySelector(`[data-type="${type}"]`);
            if (selectedBtn) {
                selectedBtn.classList.add('border-sf-accent-primary', 'bg-sf-accent-primary/20');
            }
            document.getElementById('current-animation').textContent = 
                type.charAt(0).toUpperCase() + type.slice(1) + (type === 'circular' ? ' Reveal' : ' Transition');
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            setAnimationType(currentAnimationType);
        });

        // Circular reveal animation - fixed to match sitestash.org (content stays visible)
        function createCircularReveal(event, targetTheme) {
            const button = event?.target?.closest('#theme-toggle') || event?.target;
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
            root.classList.toggle('dark', targetTheme === 'dark');

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
        }

        function createFadeTransition(targetTheme) {
            // Apply theme first
            localStorage.setItem(storageKey, targetTheme);
            root.classList.toggle('dark', targetTheme === 'dark');

            const targetBg = targetTheme === 'dark' ? '#060b1b' : '#ffffff';
            const overlay = document.createElement('div');
            overlay.className = 'theme-fade-overlay';
            overlay.style.background = targetBg;
            
            document.body.appendChild(overlay);
            
            requestAnimationFrame(() => {
                overlay.classList.add('active');
            });

            setTimeout(() => {
                overlay.classList.remove('active');
                setTimeout(() => overlay.remove(), 400);
            }, 400);

            return true;
        }

        function createSlideTransition(targetTheme) {
            // Apply theme first
            localStorage.setItem(storageKey, targetTheme);
            root.classList.toggle('dark', targetTheme === 'dark');

            const targetBg = targetTheme === 'dark' ? '#060b1b' : '#ffffff';
            const overlay = document.createElement('div');
            overlay.className = 'theme-slide-overlay';
            overlay.style.background = targetBg;
            
            document.body.appendChild(overlay);
            
            requestAnimationFrame(() => {
                overlay.classList.add('active');
            });

            setTimeout(() => {
                overlay.classList.remove('active');
                setTimeout(() => overlay.remove(), 500);
            }, 500);

            return true;
        }

        function createMorphTransition(targetTheme) {
            // Apply theme first
            localStorage.setItem(storageKey, targetTheme);
            root.classList.toggle('dark', targetTheme === 'dark');

            const targetBg = targetTheme === 'dark' ? '#060b1b' : '#ffffff';
            const overlay = document.createElement('div');
            overlay.className = 'theme-morph-overlay';
            overlay.style.background = targetBg;
            
            document.body.appendChild(overlay);
            
            requestAnimationFrame(() => {
                overlay.classList.add('active');
            });

            setTimeout(() => {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.remove(), 300);
            }, 800);

            return true;
        }

        function toggleTheme(event) {
            const current = root.classList.contains('dark') ? 'dark' : 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            
            let hasTransition = false;

            switch(currentAnimationType) {
                case 'circular':
                    hasTransition = createCircularReveal(event, next);
                    break;
                case 'fade':
                    hasTransition = createFadeTransition(next);
                    break;
                case 'slide':
                    hasTransition = createSlideTransition(next);
                    break;
                case 'morph':
                    hasTransition = createMorphTransition(next);
                    break;
            }

            if (!hasTransition) {
                // Fallback without animation
                localStorage.setItem(storageKey, next);
                root.classList.toggle('dark', next === 'dark');
            }
        }
    </script>
</body>
</html>
