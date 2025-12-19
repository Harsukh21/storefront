<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <script>
        try {
            const key = 'storefront-theme';
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

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Circular Reveal Animation - Matching sitestash.org */
        /* Content never disappears - overlay uses mix-blend-mode to create effect without hiding content */
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
    </style>
    @yield('head')
</head>
    <body class="min-h-screen bg-white text-slate-900 transition-colors duration-300 dark:bg-sf-night-900 dark:text-slate-100" data-cart-route="{{ route('front.cart.mini') }}">
        @yield('content')
        @include('shared.toast')

    <script src="{{ asset('js/toast.js') }}" defer></script>
    <script src="{{ asset('js/front.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
