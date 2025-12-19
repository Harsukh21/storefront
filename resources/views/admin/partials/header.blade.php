<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-sf-border/60 bg-white/95 backdrop-blur-md px-4 shadow-sm dark:border-sf-night-800/50 dark:bg-sf-night-800/95 md:px-6">
    <!-- Left Section -->
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Toggle -->
        <button 
            id="sidebar-toggle" 
            class="md:hidden flex h-10 w-10 items-center justify-center rounded-lg text-slate-700 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900" 
            aria-label="Toggle sidebar"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Breadcrumb Navigation -->
        <nav class="hidden md:flex items-center gap-2 text-sm" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="text-slate-500 transition-colors hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                Admin
            </a>
            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="font-semibold text-slate-900 dark:text-slate-100">
                {{ ucwords(str_replace(['admin.', '.'], ['', ' '], request()->route()->getName() ?? 'Dashboard')) }}
            </span>
        </nav>

        <!-- Current Date -->
        <div class="hidden lg:block text-xs font-medium text-slate-500 dark:text-slate-400">
            {{ now()->format('l, M j Y') }}
        </div>
    </div>

    <!-- Right Section -->
    <div class="flex items-center gap-3">
        <!-- Profile Link -->
        <a 
            href="{{ route('admin.profile.show') }}" 
            class="hidden items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900 md:flex"
            title="Profile Settings"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="hidden lg:inline">Profile</span>
        </a>

        <!-- Theme Toggle -->
        <button 
            type="button" 
            class="theme-toggle flex h-9 w-9 items-center justify-center rounded-lg border border-sf-border/60 bg-white transition-all hover:bg-slate-100 hover:border-sf-accent-primary/50 dark:border-sf-night-800/50 dark:bg-sf-night-800 dark:hover:bg-sf-night-900 dark:hover:border-sf-accent-primary/50" 
            aria-label="Toggle theme" 
            onclick="window.AdminTheme?.toggle()"
            title="Toggle theme"
        >
            <svg class="h-5 w-5 text-slate-700 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m6.364.636-1.414 1.414M21 12h-2m-.636 6.364-1.414-1.414M12 19v2m-6.364-.636 1.414-1.414M5 12H3m.636-6.364 1.414 1.414M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
        </button>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
            @csrf
            <button 
                type="submit" 
                class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] dark:text-sf-night-900"
                title="Logout"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="hidden sm:inline">Logout</span>
            </button>
        </form>
    </div>
</header>
