@extends('layouts.admin-panel')

@section('title', 'Users - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full" x-data="{ 
    filterDrawerOpen: false,
    activeFilters: {{ !empty(request('search')) || !empty(request('status')) ? 1 : 0 }}
}">
    <!-- Page Header with Filter Toggle -->
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">User Management</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Manage customer accounts and information</p>
        </div>
        <button 
            @click="filterDrawerOpen = true"
            class="relative inline-flex items-center gap-2 rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary hover:bg-slate-50 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200 dark:hover:bg-sf-night-900"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filters
            <span x-show="activeFilters > 0" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-sf-accent-primary text-xs font-bold text-sf-night-900" x-cloak>{{ activeFilters }}</span>
        </button>
    </div>

    <!-- Active Filters Badge -->
    @if(!empty(request('search')) || !empty(request('status')))
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Active filters:</span>
        @if(!empty(request('search')))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Search: {{ request('search') }}
                <a href="{{ route('admin.users.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        @if(!empty(request('status')))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Status: {{ ucfirst(request('status')) }}
                <a href="{{ route('admin.users.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-red-500 hover:text-red-600 dark:text-red-400">
            Clear all filters
        </a>
    </div>
    @endif

    <!-- Users Table -->
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
                <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Registered</th>
                        <th class="px-6 py-4">Last Login</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                    @forelse($users as $user)
                        <tr class="bg-white/60 text-slate-700 transition-colors hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $user->phone ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs font-semibold text-emerald-500">Verified</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-amber-500/10 px-2.5 py-1 text-xs font-semibold text-amber-500">Unverified</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                @if($user->last_login_at)
                                    {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a 
                                    href="{{ route('admin.users.show', $user->id) }}" 
                                    class="inline-flex items-center text-sm font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                                >
                                    View
                                    <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No users found</h3>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Try adjusting your search or filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Filter Drawer -->
    <div 
        x-show="filterDrawerOpen"
        x-cloak
        @click.away="filterDrawerOpen = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-hidden"
    >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="filterDrawerOpen = false"></div>
        
        <!-- Drawer -->
        <div 
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl dark:bg-sf-night-800"
        >
            <div class="flex h-full flex-col">
                <!-- Drawer Header -->
                <div class="flex items-center justify-between border-b border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Filter Users</h2>
                    <button 
                        @click="filterDrawerOpen = false"
                        class="rounded-lg p-1 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
                    >
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Drawer Content -->
                <div class="flex-1 overflow-y-auto px-6 py-6">
                    <form method="GET" action="{{ route('admin.users.index') }}" id="filter-form" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label for="filter_search" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Search</label>
                            <input 
                                type="text" 
                                id="filter_search" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Name, Email, Phone..." 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            />
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="filter_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Status</label>
                            <select 
                                id="filter_status" 
                                name="status" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">All Statuses</option>
                                <option value="verified" @selected(request('status') === 'verified')>Verified</option>
                                <option value="unverified" @selected(request('status') === 'unverified')>Unverified</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Drawer Footer -->
                <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                    <div class="flex gap-3">
                        <button 
                            type="button"
                            onclick="document.getElementById('filter-form').reset(); document.getElementById('filter-form').submit();"
                            class="flex-1 rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary hover:bg-slate-50 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200 dark:hover:bg-sf-night-900"
                        >
                            Clear
                        </button>
                        <button 
                            type="submit"
                            form="filter-form"
                            class="flex-1 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                        >
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
