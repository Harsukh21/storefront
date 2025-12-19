@extends('layouts.user')

@section('content')
<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Dashboard</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Welcome back, {{ auth()->user()->name ?? 'User' }}!</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid gap-6 mt-6 md:grid-cols-2 lg:grid-cols-4">
    <div class="user-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Orders</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $totalOrders }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-sf-accent-primary/10 dark:bg-sf-accent-primary/20">
                <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>
        <a href="{{ route('user.orders.index') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">
            View all
            <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <div class="user-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Spent</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">${{ number_format($totalSpent, 2) }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-sf-accent-secondary/10 dark:bg-sf-accent-secondary/20">
                <svg class="h-6 w-6 text-sf-accent-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="user-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending Orders</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $pendingOrders }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-500/10 dark:bg-yellow-500/20">
                <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="user-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Wishlist Items</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $wishlistCount }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-500/10 dark:bg-red-500/20">
                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
        </div>
        <a href="{{ route('user.wishlist.index') }}" class="mt-4 inline-flex items-center text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">
            View wishlist
            <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</div>

<div class="grid gap-6 mt-6 lg:grid-cols-2">
    <!-- Recent Orders -->
    <div class="user-card p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Recent Orders</h2>
            <a href="{{ route('user.orders.index') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View all →</a>
        </div>
        @if($recentOrders->count() > 0)
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between rounded-lg border border-sf-border/60 p-4 transition hover:bg-slate-50 dark:border-[rgba(114,138,221,0.25)] dark:hover:bg-sf-night-800">
                        <div>
                            <a href="{{ route('user.orders.show', $order->order_number) }}" class="font-semibold text-slate-900 dark:text-slate-100 transition hover:text-sf-accent-primary">{{ $order->order_number }}</a>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($order->placed_at ?? $order->created_at)->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($order->grand_total, 2) }}</p>
                            <span class="mt-1 inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold capitalize {{ $order->status === 'delivered' ? 'bg-emerald-500/10 text-emerald-400' : ($order->status === 'cancelled' ? 'bg-red-500/10 text-red-400' : 'bg-blue-500/10 text-blue-400') }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="mt-4 text-sm font-medium text-slate-900 dark:text-slate-100">No orders yet</p>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Start shopping to see your orders here</p>
                <a href="{{ route('front.products') }}" class="mt-4 inline-flex items-center rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">
                    Browse Products
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Links -->
    <div class="user-card p-6">
        <h2 class="mb-6 text-lg font-semibold text-slate-900 dark:text-slate-100">Quick Links</h2>
        <div class="space-y-3">
            <a href="{{ route('user.profile.show') }}" class="flex items-center justify-between rounded-lg border border-sf-border/60 bg-white px-4 py-3 transition hover:border-sf-accent-primary hover:bg-sf-accent-primary/5 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:hover:bg-sf-night-800/80">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sf-accent-primary/10 dark:bg-sf-accent-primary/20">
                        <svg class="h-5 w-5 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Profile Settings</span>
                </div>
                <svg class="h-5 w-5 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <a href="{{ route('user.addresses.index') }}" class="flex items-center justify-between rounded-lg border border-sf-border/60 bg-white px-4 py-3 transition hover:border-sf-accent-primary hover:bg-sf-accent-primary/5 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:hover:bg-sf-night-800/80">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sf-accent-secondary/10 dark:bg-sf-accent-secondary/20">
                        <svg class="h-5 w-5 text-sf-accent-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Addresses ({{ $addressCount }})</span>
                </div>
                <svg class="h-5 w-5 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <a href="{{ route('user.payment-methods.index') }}" class="flex items-center justify-between rounded-lg border border-sf-border/60 bg-white px-4 py-3 transition hover:border-sf-accent-primary hover:bg-sf-accent-primary/5 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:hover:bg-sf-night-800/80">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-500/10 dark:bg-green-500/20">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Payment Methods</span>
                </div>
                <svg class="h-5 w-5 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <a href="{{ route('user.wishlist.index') }}" class="flex items-center justify-between rounded-lg border border-sf-border/60 bg-white px-4 py-3 transition hover:border-sf-accent-primary hover:bg-sf-accent-primary/5 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:hover:bg-sf-night-800/80">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-500/10 dark:bg-red-500/20">
                        <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Wishlist</span>
                </div>
                <svg class="h-5 w-5 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection
