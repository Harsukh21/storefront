<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-sf-border/60 bg-white/95 backdrop-blur-sm dark:border-sf-night-800/50 dark:bg-sf-night-800/95">
    <div class="flex items-center gap-4 px-4 md:px-6">
        <button id="sidebar-toggle" class="md:hidden" aria-label="Toggle sidebar">
            <svg class="h-6 w-6 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <nav class="hidden text-sm text-slate-600 dark:text-slate-400 md:flex md:items-center md:gap-2">
            <a href="{{ route('front.home') }}" class="hover:text-slate-900 dark:hover:text-slate-100">Home</a>
            <span>/</span>
            <span class="text-slate-900 dark:text-slate-100">
                @if(request()->routeIs('user.dashboard'))
                    Dashboard
                @elseif(request()->routeIs('user.orders.*'))
                    Orders
                @elseif(request()->routeIs('user.wishlist.*'))
                    Wishlist
                @elseif(request()->routeIs('user.profile.*'))
                    Profile
                @elseif(request()->routeIs('user.addresses.*'))
                    Addresses
                @elseif(request()->routeIs('user.payment-methods.*'))
                    Payment Methods
                @else
                    Account
                @endif
            </span>
        </nav>
    </div>

    <div class="flex items-center gap-3 px-4 md:px-6">
        <a href="{{ route('front.home') }}" class="hidden rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200 md:inline-flex md:items-center md:gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Back to Store</span>
        </a>

        <!-- Theme Toggle -->
        <button id="theme-toggle" type="button" class="flex h-9 w-9 items-center justify-center rounded-lg border border-sf-border/60 bg-white text-slate-700 transition hover:border-sf-accent-primary hover:bg-slate-50 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200 dark:hover:bg-sf-night-700" aria-label="Toggle theme">
            <!-- Sun icon (light mode) -->
            <svg id="theme-toggle-light-icon" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <!-- Moon icon (dark mode) -->
            <svg id="theme-toggle-dark-icon" class="h-5 w-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>

        <div class="relative" x-data="{ open: false }" x-cloak>
            <button @click="open = !open" class="flex items-center gap-2 rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">
                <div class="flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary">
                    <span class="text-xs font-bold text-white">{{ substr(optional(auth()->user())->name ?? 'U', 0, 1) }}</span>
                </div>
                <span class="hidden md:inline">{{ optional(auth()->user())->name ?? 'User' }}</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 rounded-lg border border-sf-border/60 bg-white shadow-lg dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800">
                <div class="p-2">
                    <a href="{{ route('user.profile.show') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile Settings
                    </a>
                    <a href="{{ route('user.addresses.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Addresses
                    </a>
                    <a href="{{ route('user.payment-methods.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Payment Methods
                    </a>
                    <div class="my-1 border-t border-sf-border/60 dark:border-sf-night-800/50"></div>
                    <form method="POST" action="{{ route('user.logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-red-500 transition hover:bg-red-50 dark:hover:bg-red-900/20">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

