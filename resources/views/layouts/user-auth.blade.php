<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — {{ $title ?? 'Account' }}</title>

    <script>
        try {
            const key = 'storefront-user-theme';
            const stored = localStorage.getItem(key);
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        } catch (error) {
            console.warn('Unable to read user theme preference', error);
        }
    </script>

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
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
                        },
                        'sf-text': {
                            primary: '#0f172a',
                            inverted: '#f2f7ff',
                        },
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
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    @yield('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-sf-night-900 dark:text-slate-100">
    <!-- Simple Header -->
    <header class="border-b border-sf-border/60 bg-white/95 backdrop-blur-sm dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800/95">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ route('front.home') }}" class="flex items-center gap-2 text-lg font-semibold tracking-wide text-slate-900 dark:text-sf-accent-primary">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary text-sf-night-900 font-bold text-sm">{{ strtoupper(substr(preg_replace('/[^A-Za-z]/', '', config('app.name', 'StoreFront')), 0, 2)) ?: 'SF' }}</span>
                <span>{{ config('app.name', 'StoreFront') }}</span>
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('front.home') }}" class="hidden rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200 md:inline-flex md:items-center md:gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Back to Store</span>
                </a>
                <button id="theme-toggle" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-sf-border/60 bg-white text-slate-700 transition hover:border-sf-accent-primary hover:bg-slate-50 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200 dark:hover:bg-sf-night-700" aria-label="Toggle theme">
                    <svg id="theme-toggle-light-icon" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg id="theme-toggle-dark-icon" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-73px)]">
        @yield('content')
    </main>

    @include('shared.toast')

    <script>
        // Theme toggle
        document.addEventListener('DOMContentLoaded', function() {
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

            // Initialize toast notifications from session
            @if(session('toast'))
                const toastData = @json(session('toast'));
                if (window.Toast && toastData) {
                    if (Array.isArray(toastData)) {
                        toastData.forEach(t => window.Toast.show(t));
                    } else {
                        window.Toast.show(toastData);
                    }
                }
            @endif
        });
    </script>
    <script src="{{ asset('js/toast.js') }}" defer></script>
    @yield('scripts')
</body>
</html>

