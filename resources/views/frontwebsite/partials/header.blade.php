<header class="relative z-50 border-b border-sf-border bg-white/85 backdrop-blur dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-900/90" style="overflow: visible !important;">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5" style="overflow: visible !important; position: relative;">
        @php
            $appName = config('app.name', 'StoreFront');
            $brandInitials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $appName), 0, 2)) ?: 'SF';
        @endphp
        <a href="{{ route('front.home') }}" class="flex items-center gap-2 text-xl font-semibold tracking-wide text-slate-900 dark:text-sf-accent-primary">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary text-sf-night-900 font-bold">{{ $brandInitials }}</span>
            <span>{{ $appName }}</span>
        </a>
        <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 dark:text-slate-300 md:flex">
            <a href="{{ route('front.products') }}" class="front-nav-link">Products</a>
            <a href="{{ route('front.categories') }}" class="front-nav-link">Categories</a>
            <a href="{{ route('front.brands') }}" class="front-nav-link">Brands</a>
        </nav>
        <div class="flex items-center gap-3">
            <button type="button" class="theme-toggle" aria-label="Toggle theme" onclick="window.StorefrontTheme?.toggle()">
                <span class="sr-only">Toggle theme</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v2m6.364.636-1.414 1.414M21 12h-2m-.636 6.364-1.414-1.414M12 19v2m-6.364-.636 1.414-1.414M5 12H3m.636-6.364 1.414 1.414M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </button>
            <a href="{{ route('front.cart.index') }}" class="relative theme-toggle" aria-label="Shopping cart">
                <span class="sr-only">Shopping cart</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                <span id="cart-count" class="absolute -top-1 -right-1 hidden h-5 w-5 items-center justify-center rounded-full bg-sf-accent-primary text-xs font-semibold text-white"></span>
            </a>
            @auth
                <div class="relative z-50 hidden md:block" x-data="{ open: false }" x-cloak>
                    <button @click="open = !open" class="flex items-center gap-2 rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">
                        <div class="flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary">
                            <span class="text-xs font-bold text-white">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <span class="hidden lg:inline">{{ auth()->user()->name ?? 'User' }}</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-[9999] mt-2 w-48 rounded-lg border border-sf-border/60 bg-white shadow-xl dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800" style="z-index: 9999 !important;">
                        <div class="p-2">
                            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                            <a href="{{ route('user.profile.show') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile Settings
                            </a>
                            <a href="{{ route('user.orders.index') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                My Orders
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
            @else
                <a href="{{ route('user.login') }}" class="hidden text-sm font-semibold text-slate-600 transition hover:text-sf-accent-primary dark:text-slate-300 dark:hover:text-sf-accent-primary md:inline">Sign In</a>
                <a href="{{ route('front.products') }}" class="hidden rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-sf-night-900 shadow-sf-glow transition hover:opacity-90 md:inline">Start Shopping</a>
            @endauth
            <button type="button" class="md:hidden theme-toggle" aria-label="Toggle navigation" data-front-menu-toggle>
                <span class="sr-only">Toggle navigation</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>
    <nav data-front-mobile-menu class="front-mobile-menu hidden border-t border-sf-border bg-white px-6 pb-6 pt-4 text-sm font-medium text-slate-700 dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-900 dark:text-slate-200 md:hidden">
        <div class="flex flex-col gap-3">
            <a href="{{ route('front.products') }}" class="front-mobile-link">Products</a>
            <a href="{{ route('front.categories') }}" class="front-mobile-link">Categories</a>
            <a href="{{ route('front.brands') }}" class="front-mobile-link">Brands</a>
        </div>
        <div class="mt-6 flex flex-col gap-3">
            <a href="{{ route('front.cart.index') }}" class="rounded-full border border-sf-accent-primary px-4 py-2 text-center font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/10">Cart</a>
            @auth
                <a href="{{ route('user.dashboard') }}" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-center font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Dashboard</a>
                <form method="POST" action="{{ route('user.logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-full border border-red-500/70 px-4 py-2 text-center font-semibold text-red-500 transition hover:bg-red-50 dark:hover:bg-red-900/20">Logout</button>
                </form>
            @else
                <a href="{{ route('user.login') }}" class="rounded-full border border-sf-accent-primary px-4 py-2 text-center font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/10">Sign In</a>
                <a href="{{ route('front.products') }}" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-center font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Start Shopping</a>
            @endauth
        </div>
    </nav>
</header>
