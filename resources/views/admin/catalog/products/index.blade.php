@extends('layouts.admin-panel')

@section('title', 'Products - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full" x-data="{ 
    filterDrawerOpen: false,
    activeFilters: {{ !empty($filters['search']) || !empty($filters['status']) || !empty($filters['category_id']) || !empty($filters['brand_id']) ? 1 : 0 }}
}" 
x-effect="document.body.style.overflow = filterDrawerOpen ? 'hidden' : ''"
:class="{ 'pointer-events-none': filterDrawerOpen }">
    <!-- Page Header with Filter Toggle -->
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Products</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Manage storefront products and availability</p>
        </div>
        <div class="flex items-center gap-3">
            <!-- Filter Button -->
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
            <!-- New Product Button -->
            <a 
                href="{{ route('admin.catalog.products.create') }}" 
                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Product
            </a>
        </div>
    </div>

    <!-- Active Filters Badge -->
    @if(!empty($filters['search']) || !empty($filters['status']) || !empty($filters['category_id']) || !empty($filters['brand_id']))
    <div class="mb-6 flex flex-wrap items-center gap-2">
        <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Active filters:</span>
        @if(!empty($filters['search']))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Search: {{ $filters['search'] }}
                <a href="{{ route('admin.catalog.products.index', array_merge(request()->except('search'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        @if(!empty($filters['status']))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Status: {{ ucfirst($filters['status']) }}
                <a href="{{ route('admin.catalog.products.index', array_merge(request()->except('status'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        @if(!empty($filters['category_id']))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Category: {{ DB::table('categories')->where('id', $filters['category_id'])->value('name') ?? 'N/A' }}
                <a href="{{ route('admin.catalog.products.index', array_merge(request()->except('category_id'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        @if(!empty($filters['brand_id']))
            <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary">
                Brand: {{ DB::table('brands')->where('id', $filters['brand_id'])->value('name') ?? 'N/A' }}
                <a href="{{ route('admin.catalog.products.index', array_merge(request()->except('brand_id'), ['page' => 1])) }}" class="ml-1 hover:text-sf-accent-secondary">
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </span>
        @endif
        <a href="{{ route('admin.catalog.products.index') }}" class="text-sm font-semibold text-red-500 hover:text-red-600 dark:text-red-400">
            Clear all filters
        </a>
    </div>
    @endif

    <!-- Products Table -->
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
                <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Brand</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                    @forelse ($products as $product)
                        <tr class="bg-white/60 text-slate-700 transition-colors hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $product->name }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $product->slug }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $product->category_name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $product->brand_name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-slate-100">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                    {{ $product->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex gap-3">
                                    <a 
                                        href="{{ route('admin.catalog.products.edit', $product->id) }}" 
                                        class="text-sm font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                                    >
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.catalog.products.destroy', $product->id) }}" onsubmit="return confirm('Delete this product?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-red-500 transition-colors hover:text-red-600 dark:text-red-400">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No products found</h3>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                        <a href="{{ route('admin.catalog.products.create') }}" class="font-semibold text-sf-accent-primary hover:text-sf-accent-secondary">Create your first product</a>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                {{ $products->links() }}
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
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Filter Products</h2>
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
                    <form method="GET" action="{{ route('admin.catalog.products.index') }}" id="filter-form" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label for="filter_search" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Search</label>
                            <input 
                                type="text" 
                                id="filter_search" 
                                name="search" 
                                value="{{ $filters['search'] ?? '' }}" 
                                placeholder="Search by name or SKU" 
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
                                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Hidden</option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="filter_category" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Category</label>
                            <select 
                                id="filter_category" 
                                name="category_id" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">All Categories</option>
                                @foreach(DB::table('categories')->orderBy('name')->get() as $category)
                                    <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Brand -->
                        <div>
                            <label for="filter_brand" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Brand</label>
                            <select 
                                id="filter_brand" 
                                name="brand_id" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">All Brands</option>
                                @foreach(DB::table('brands')->orderBy('name')->get() as $brand)
                                    <option value="{{ $brand->id }}" @selected(($filters['brand_id'] ?? '') == $brand->id)>{{ $brand->name }}</option>
                                @endforeach
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
