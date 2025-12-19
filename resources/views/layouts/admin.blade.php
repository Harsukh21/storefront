<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name') . ' Admin')</title>

    <!-- Theme Initialization -->
    <script>
        try {
            const key = 'storefront-admin-theme';
            const stored = localStorage.getItem(key);
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (stored === 'dark' || (!stored && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        } catch (error) {
            console.warn('Unable to read admin theme preference', error);
        }
    </script>

    <!-- Tailwind CSS -->
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
                        'sf-border': '#d6dcff',
                    },
                    boxShadow: {
                        'sf-glow': '0 25px 70px -45px rgba(10, 22, 55, 0.45)',
                        'sf-card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                    },
                },
            },
        };
    </script>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @yield('head')
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:from-sf-night-900 dark:via-sf-night-800 dark:to-sf-night-900 dark:text-slate-100">
    <!-- Main Content -->
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        @yield('content')
    </div>

    <!-- Toast Notifications -->
    @include('shared.toast')

    <!-- Scripts -->
    <script src="{{ asset('js/toast.js') }}" defer></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
