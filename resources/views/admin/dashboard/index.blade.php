@extends('layouts.admin-panel')

@section('title', 'Dashboard - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Dashboard</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Welcome back, <span class="font-semibold text-sf-accent-primary">{{ auth('admin')->user()->name ?? 'Admin' }}</span>! Here's what's happening with your store.
        </p>
    </div>

    <!-- Metrics Cards -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Products Card -->
        <div class="admin-card p-6 transition-all hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Products</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($metrics['products']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                    <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.catalog.products.index') }}" class="mt-4 inline-flex items-center text-xs font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary">
                View all <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>

        <!-- Categories Card -->
        <div class="admin-card p-6 transition-all hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Categories</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($metrics['categories']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                    <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.catalog.categories.index') }}" class="mt-4 inline-flex items-center text-xs font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary">
                View all <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>

        <!-- Brands Card -->
        <div class="admin-card p-6 transition-all hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Brands</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($metrics['brands']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                    <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.catalog.brands.index') }}" class="mt-4 inline-flex items-center text-xs font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary">
                View all <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>

        <!-- Low Stock Card -->
        <div class="admin-card p-6 transition-all hover:shadow-lg {{ $metrics['lowStock'] > 0 ? 'border-2 border-amber-500/50' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Low Stock</p>
                    <p class="mt-2 text-3xl font-bold {{ $metrics['lowStock'] > 0 ? 'text-amber-500' : 'text-slate-900 dark:text-slate-100' }}">{{ number_format($metrics['lowStock']) }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $metrics['lowStock'] > 0 ? 'bg-amber-500/20' : 'bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20' }}">
                    <svg class="h-6 w-6 {{ $metrics['lowStock'] > 0 ? 'text-amber-500' : 'text-sf-accent-primary' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.inventory.index') }}" class="mt-4 inline-flex items-center text-xs font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary">
                Manage inventory <svg class="ml-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Latest Products -->
        <section class="admin-card p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Latest Products</h2>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Recently added products</p>
                </div>
                <a href="{{ route('admin.catalog.products.index') }}" class="rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary hover:text-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">
                    View All
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($recentProducts as $product)
                    <div class="flex items-center justify-between rounded-lg border border-sf-border/60 px-4 py-3 transition-colors hover:bg-slate-50 dark:border-[rgba(114,138,221,0.25)] dark:hover:bg-sf-night-900/50">
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $product->name }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Added {{ $product->created_at ? \Carbon\Carbon::parse($product->created_at)->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                        <span class="ml-4 rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                            {{ $product->is_active ? 'Active' : 'Draft' }}
                        </span>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-sf-border/60 px-4 py-8 text-center text-sm text-slate-500 dark:border-[rgba(114,138,221,0.25)] dark:text-slate-400">
                        No products yet. <a href="{{ route('admin.catalog.products.create') }}" class="font-semibold text-sf-accent-primary hover:text-sf-accent-secondary">Create your first product</a>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Recent Stock Activity -->
        <section class="admin-card p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Recent Stock Activity</h2>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Latest inventory adjustments</p>
                </div>
                <a href="{{ route('admin.inventory.adjustments.index') }}" class="rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary hover:text-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">
                    View All
                </a>
            </div>
            <div class="space-y-3">
                @forelse ($recentAdjustments as $adjustment)
                    <div class="rounded-lg border border-sf-border/60 px-4 py-3 transition-colors hover:bg-slate-50 dark:border-[rgba(114,138,221,0.25)] dark:hover:bg-sf-night-900/50">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $adjustment->product_name ?? 'Unknown product' }}</p>
                            <span class="ml-4 rounded-full px-2.5 py-1 text-xs font-semibold {{ ($adjustment->quantity_change ?? 0) >= 0 ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500' }}">
                                {{ ($adjustment->quantity_change ?? 0) >= 0 ? '+' : '' }}{{ $adjustment->quantity_change }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ ucfirst($adjustment->adjustment_type ?? 'manual') }} • {{ $adjustment->created_at ? \Carbon\Carbon::parse($adjustment->created_at)->diffForHumans() : 'N/A' }}
                        </p>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-sf-border/60 px-4 py-8 text-center text-sm text-slate-500 dark:border-[rgba(114,138,221,0.25)] dark:text-slate-400">
                        No adjustments recorded yet.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
