<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} — Account</title>

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
                        },
                        'sf-accent': {
                            primary: '#29ffc6',
                            secondary: '#7c5cff',
                        },
                        'sf-text': {
                            primary: '#0f172a',
                            inverted: '#f2f7ff',
                        },
                    },
                },
            },
        };
    </script>

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @yield('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 transition-colors duration-300 dark:bg-sf-night-900 dark:text-slate-100">
    <div class="flex min-h-screen">
        @include('user.partials.sidebar')

        <div class="flex min-h-screen flex-1 flex-col md:ml-64">
            @include('user.partials.header')

            <main class="flex-1 px-4 py-6 md:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>

    @include('shared.toast')

    <script>
        // Initialize toast notifications from session
        document.addEventListener('DOMContentLoaded', function() {
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
    <script src="{{ asset('js/user.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
