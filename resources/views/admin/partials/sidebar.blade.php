@php
    $menuItems = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'match' => 'admin.dashboard',
            'icon' => 'dashboard',
        ],
        [
            'label' => 'Orders',
            'route' => 'admin.orders.index',
            'match' => 'admin.orders.*',
            'icon' => 'shopping-cart',
        ],
        [
            'label' => 'User Management',
            'icon' => 'users',
            'match' => 'admin.users.*|admin.admins.*',
            'children' => [
                [
                    'label' => 'Users',
                    'route' => 'admin.users.index',
                    'match' => 'admin.users.*',
                ],
                [
                    'label' => 'Admins',
                    'route' => 'admin.admins.index',
                    'match' => 'admin.admins.*',
                ],
            ],
        ],
        [
            'label' => 'Catalog',
            'icon' => 'folder',
            'match' => 'admin.catalog.*',
            'children' => [
                [
                    'label' => 'Categories',
                    'route' => 'admin.catalog.categories.index',
                    'match' => 'admin.catalog.categories.*',
                ],
                [
                    'label' => 'Brands',
                    'route' => 'admin.catalog.brands.index',
                    'match' => 'admin.catalog.brands.*',
                ],
                [
                    'label' => 'Products',
                    'route' => 'admin.catalog.products.index',
                    'match' => 'admin.catalog.products.*',
                ],
            ],
        ],
        [
            'label' => 'Inventory',
            'icon' => 'cube',
            'match' => 'admin.inventory.*',
            'children' => [
                [
                    'label' => 'Inventory Items',
                    'route' => 'admin.inventory.index',
                    'match' => 'admin.inventory.index|admin.inventory.store',
                ],
                [
                    'label' => 'Adjustments',
                    'route' => 'admin.inventory.adjustments.index',
                    'match' => 'admin.inventory.adjustments.*',
                ],
            ],
        ],
        [
            'label' => 'Pricing',
            'icon' => 'tag',
            'match' => 'admin.discounts.*|admin.tax-rates.*',
            'children' => [
                [
                    'label' => 'Discounts',
                    'route' => 'admin.discounts.index',
                    'match' => 'admin.discounts.*',
                ],
                [
                    'label' => 'Tax Rates',
                    'route' => 'admin.tax-rates.index',
                    'match' => 'admin.tax-rates.*',
                ],
            ],
        ],
        [
            'label' => 'Payments',
            'route' => 'admin.payments.index',
            'match' => 'admin.payments.*',
            'icon' => 'credit-card',
        ],
        [
            'label' => 'Reviews',
            'route' => 'admin.reviews.index',
            'match' => 'admin.reviews.*',
            'icon' => 'star',
        ],
        [
            'label' => 'Questions',
            'route' => 'admin.questions.index',
            'match' => 'admin.questions.*',
            'icon' => 'question-mark-circle',
        ],
        [
            'label' => 'Activity Logs',
            'route' => 'admin.activity-logs.index',
            'match' => 'admin.activity-logs.*',
            'icon' => 'document-text',
        ],
        [
            'label' => 'Notifications',
            'route' => 'admin.notifications.index',
            'match' => 'admin.notifications.*',
            'icon' => 'bell',
        ],
    ];
@endphp

<!-- Sidebar -->
<aside 
    id="admin-sidebar" 
    class="fixed left-0 top-0 z-30 h-screen w-64 -translate-x-full transform border-r border-sf-border/60 bg-white shadow-xl transition-transform duration-300 ease-in-out dark:border-sf-night-800/50 dark:bg-sf-night-800 md:translate-x-0"
    aria-label="Main navigation"
>
    <!-- Sidebar Header -->
    <div class="flex h-16 items-center justify-between border-b border-sf-border/60 px-6 dark:border-sf-night-800/50">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary shadow-lg transition-transform group-hover:scale-105">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ config('app.name') }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Admin Panel</p>
            </div>
        </a>
        <button 
            id="sidebar-close" 
            class="md:hidden flex h-8 w-8 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200" 
            aria-label="Close sidebar"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-3 py-4" aria-label="Sidebar navigation">
        <ul class="space-y-1">
            @foreach ($menuItems as $item)
                @php
                    $hasChildren = isset($item['children']);
                    $isActive = request()->routeIs($item['match'] ?? '');
                    if ($hasChildren) {
                        $childMatches = collect($item['children'])->pluck('match')->filter()->toArray();
                        $isOpen = $isActive || request()->routeIs($childMatches);
                    } else {
                        $isOpen = false;
                    }
                @endphp

                @if($hasChildren)
                    <li>
                        <button 
                            type="button" 
                            class="menu-toggle w-full flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $isActive || $isOpen ? 'bg-sf-accent-primary/10 text-sf-accent-primary shadow-sm' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900' }}" 
                            data-menu="{{ $loop->index }}"
                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                        >
                            <div class="flex items-center gap-3">
                                @include('admin.partials.icons.' . $item['icon'])
                                <span>{{ $item['label'] }}</span>
                            </div>
                            <svg 
                                class="h-4 w-4 transition-transform duration-200 {{ $isOpen ? 'rotate-90' : '' }}" 
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor" 
                                stroke-width="2"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <ul 
                            class="submenu mt-1 space-y-1 overflow-hidden {{ $isOpen ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}" 
                            data-submenu="{{ $loop->index }}" 
                            data-open="{{ $isOpen ? 'true' : 'false' }}"
                        >
                            @foreach ($item['children'] as $child)
                                @php $childActive = request()->routeIs($child['match']); @endphp
                                <li>
                                    <a 
                                        href="{{ route($child['route']) }}" 
                                        class="flex items-center gap-3 rounded-lg px-3 py-2 pl-11 text-sm transition-all duration-200 {{ $childActive ? 'bg-sf-accent-primary/15 text-sf-accent-primary font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-sf-night-900' }}"
                                    >
                                        <span class="h-1.5 w-1.5 rounded-full {{ $childActive ? 'bg-sf-accent-primary' : 'bg-slate-400' }}"></span>
                                        <span>{{ $child['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        <a 
                            href="{{ route($item['route']) }}" 
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $isActive ? 'bg-sf-accent-primary/10 text-sf-accent-primary shadow-sm' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900' }}"
                        >
                            @include('admin.partials.icons.' . $item['icon'])
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="border-t border-sf-border/60 px-4 py-4 dark:border-sf-night-800/50">
        <!-- User Profile Card -->
        <div class="flex items-center gap-3 rounded-lg bg-slate-50 p-3 dark:bg-sf-night-900/50">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary shadow-md">
                <span class="text-sm font-bold text-white">{{ substr(optional(auth('admin')->user())->name ?? 'A', 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ optional(auth('admin')->user())->name ?? 'Admin' }}
                </p>
                <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                    {{ optional(auth('admin')->user())->email ?? '' }}
                </p>
            </div>
        </div>
        
        <!-- Account Settings Link -->
        <a 
            href="{{ route('admin.profile.show') }}" 
            class="mt-3 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Account Settings</span>
        </a>
    </div>
</aside>

<!-- Mobile Overlay -->
<div 
    id="sidebar-overlay" 
    class="fixed inset-0 z-20 bg-black/50 opacity-0 transition-opacity duration-300 md:hidden pointer-events-none"
    aria-hidden="true"
></div>
