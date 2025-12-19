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
    <!-- Alpine.js for drawer functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
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
                        'sf-card-hover': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                    },
                },
            },
        };
    </script>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- Enhanced Form Controls for Dark Mode -->
    <style>
        /* Form Controls - Dark Mode Overrides */
        html.dark input.rounded-md,
        html.dark select.rounded-md,
        html.dark textarea.rounded-md {
            background-color: rgba(22, 34, 70, 0.8) !important;
            color: #f2f7ff !important;
            border-color: rgba(114, 138, 221, 0.35) !important;
        }
        
        html.dark input.rounded-md:focus,
        html.dark select.rounded-md:focus,
        html.dark textarea.rounded-md:focus {
            background-color: rgba(22, 34, 70, 0.95) !important;
            border-color: #29ffc6 !important;
            box-shadow: 0 0 0 3px rgba(41, 255, 198, 0.2) !important;
            color: #f2f7ff !important;
        }
        
        html.dark input.rounded-md::placeholder,
        html.dark textarea.rounded-md::placeholder {
            color: rgba(148, 163, 184, 0.7) !important;
        }

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Loading State */
        .loading {
            opacity: 0.6;
            pointer-events: none;
            cursor: wait;
        }
    </style>
    
    @yield('head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-sf-night-900 dark:text-slate-100">
    <!-- Main Container -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')

        <!-- Main Content Area -->
        <div class="flex min-h-screen flex-1 flex-col md:ml-64 relative z-40">
            <!-- Header -->
            @include('admin.partials.header')

            <!-- Page Content -->
            <main class="flex-1 px-4 py-6 md:px-6 lg:px-8 relative z-10">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notifications -->
    @include('shared.toast')

    <!-- Scripts -->
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
    <script src="{{ asset('js/admin.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
