@extends('layouts.admin-panel')

@section('title', 'Orders - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full" x-data="{ 
    filterDrawerOpen: false,
    activeFilters: {{ !empty(request('search')) || !empty(request('status')) || !empty(request('payment_status')) || !empty(request('fulfillment_status')) || !empty(request('date_from')) || !empty(request('date_to')) ? 1 : 0 }}
}" 
x-effect="document.body.style.overflow = filterDrawerOpen ? 'hidden' : ''"
:class="{ 'pointer-events-none': filterDrawerOpen }">
    <!-- Page Header with Filter Toggle -->
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Orders</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Manage customer orders and fulfillment</p>
        </div>
        <button 
            @click="filterDrawerOpen = true"
            class="relative inline-flex items-center gap-2 rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary hover:bg-slate-50 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200 dark:hover:bg-sf-night-900"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            Filters
            <span x-show="activeFilters > 0" x-text="activeFilters" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-sf-accent-primary text-xs font-bold text-sf-night-900" x-cloak></span>
        </button>
    </div>

    <!-- Active Filters Badge -->
    @php
        $hasFilters = !empty(request('search')) || !empty(request('status')) || !empty(request('payment_status')) || !empty(request('fulfillment_status')) || !empty(request('date_from')) || !empty(request('date_to'));
    @endphp
    @if($hasFilters)
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Active filters:</span>
        @if(!empty(request('search')))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Search: {{ request('search') }}
                <a href="{{ route('admin.orders.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        @if(!empty(request('status')))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Status: {{ ucfirst(request('status')) }}
                <a href="{{ route('admin.orders.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        @if(!empty(request('payment_status')))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Payment: {{ ucfirst(request('payment_status')) }}
                <a href="{{ route('admin.orders.index', array_merge(request()->except('payment_status'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        @if(!empty(request('fulfillment_status')))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Fulfillment: {{ ucfirst(str_replace('_', ' ', request('fulfillment_status'))) }}
                <a href="{{ route('admin.orders.index', array_merge(request()->except('fulfillment_status'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        @if(!empty(request('date_from')) || !empty(request('date_to')))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Date: {{ request('date_from') ?? 'Any' }} to {{ request('date_to') ?? 'Any' }}
                <a href="{{ route('admin.orders.index', array_merge(request()->except(['date_from', 'date_to']), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-red-500 hover:text-red-600 dark:text-red-400">
            Clear all filters
        </a>
    </div>
    @endif

    <!-- Orders Table -->
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
                <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">Order #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">Fulfillment</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                    @forelse($orders as $order)
                        <tr class="bg-white/60 text-slate-700 transition-colors hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                            <td class="px-6 py-4">
                                <a 
                                    href="{{ route('admin.orders.show', $order->order_number) }}" 
                                    class="font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                                >
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->user_name)
                                    <p class="font-medium text-slate-900 dark:text-slate-100">{{ $order->user_name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $order->user_email }}</p>
                                @else
                                    <span class="text-slate-500 dark:text-slate-400">Guest</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $order->status === 'delivered' ? 'bg-emerald-500/10 text-emerald-500' : ($order->status === 'cancelled' ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500') }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $order->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-500' : ($order->payment_status === 'failed' ? 'bg-red-500/10 text-red-500' : 'bg-amber-500/10 text-amber-500') }}">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $order->fulfillment_status === 'fulfilled' ? 'bg-emerald-500/10 text-emerald-500' : ($order->fulfillment_status === 'unfulfilled' ? 'bg-slate-500/10 text-slate-500 dark:text-slate-400' : 'bg-blue-500/10 text-blue-500') }}">
                                    {{ str_replace('_', ' ', $order->fulfillment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">${{ number_format($order->grand_total, 2) }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($order->placed_at ?? $order->created_at)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a 
                                    href="{{ route('admin.orders.show', $order->order_number) }}" 
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
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No orders found</h3>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Try adjusting your search or filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                {{ $orders->links() }}
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
        class="fixed inset-0 z-[100] overflow-hidden"
        style="pointer-events: auto;"
    >
        <!-- Backdrop - covers entire screen including header -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="filterDrawerOpen = false" style="pointer-events: auto; z-index: 1;"></div>
        
        <!-- Drawer -->
        <div 
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl dark:bg-sf-night-800"
            style="pointer-events: auto; z-index: 2;"
            @click.stop
        >
            <div class="flex h-full flex-col">
                <!-- Drawer Header -->
                <div class="flex items-center justify-between border-b border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Filter Orders</h2>
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
                    <form method="GET" action="{{ route('admin.orders.index') }}" id="filter-form" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label for="filter_search" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Search</label>
                            <input 
                                type="text" 
                                id="filter_search" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Order #, Customer..." 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            />
                        </div>

                        <!-- Order Status -->
                        <div>
                            <label for="filter_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Order Status</label>
                            <select 
                                id="filter_status" 
                                name="status" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">All Statuses</option>
                                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                                <option value="processing" @selected(request('status') === 'processing')>Processing</option>
                                <option value="shipped" @selected(request('status') === 'shipped')>Shipped</option>
                                <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
                                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                                <option value="refunded" @selected(request('status') === 'refunded')>Refunded</option>
                            </select>
                        </div>

                        <!-- Payment Status -->
                        <div>
                            <label for="filter_payment_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Payment Status</label>
                            <select 
                                id="filter_payment_status" 
                                name="payment_status" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">All Payment Statuses</option>
                                <option value="pending" @selected(request('payment_status') === 'pending')>Pending</option>
                                <option value="paid" @selected(request('payment_status') === 'paid')>Paid</option>
                                <option value="failed" @selected(request('payment_status') === 'failed')>Failed</option>
                                <option value="refunded" @selected(request('payment_status') === 'refunded')>Refunded</option>
                            </select>
                        </div>

                        <!-- Fulfillment Status -->
                        <div>
                            <label for="filter_fulfillment_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Fulfillment Status</label>
                            <select 
                                id="filter_fulfillment_status" 
                                name="fulfillment_status" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">All Fulfillment Statuses</option>
                                <option value="unfulfilled" @selected(request('fulfillment_status') === 'unfulfilled')>Unfulfilled</option>
                                <option value="partially_fulfilled" @selected(request('fulfillment_status') === 'partially_fulfilled')>Partially Fulfilled</option>
                                <option value="fulfilled" @selected(request('fulfillment_status') === 'fulfilled')>Fulfilled</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="filter_date_from" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Date From</label>
                                <input 
                                    type="date" 
                                    id="filter_date_from" 
                                    name="date_from" 
                                    value="{{ request('date_from') }}" 
                                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                                />
                            </div>
                            <div>
                                <label for="filter_date_to" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Date To</label>
                                <input 
                                    type="date" 
                                    id="filter_date_to" 
                                    name="date_to" 
                                    value="{{ request('date_to') }}" 
                                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                                />
                            </div>
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
