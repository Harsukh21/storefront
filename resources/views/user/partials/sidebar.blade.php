@php
    $menuItems = [
        [
            'label' => 'Dashboard',
            'route' => 'user.dashboard',
            'match' => 'user.dashboard',
            'icon' => 'dashboard',
        ],
        [
            'label' => 'Orders',
            'route' => 'user.orders.index',
            'match' => 'user.orders.*',
            'icon' => 'shopping-cart',
        ],
        [
            'label' => 'Wishlist',
            'route' => 'user.wishlist.index',
            'match' => 'user.wishlist.*',
            'icon' => 'heart',
        ],
        [
            'label' => 'Account',
            'icon' => 'user',
            'match' => 'user.profile.*|user.addresses.*|user.payment-methods.*',
            'children' => [
                [
                    'label' => 'Profile',
                    'route' => 'user.profile.show',
                    'match' => 'user.profile.*',
                ],
                [
                    'label' => 'Addresses',
                    'route' => 'user.addresses.index',
                    'match' => 'user.addresses.*',
                ],
                [
                    'label' => 'Payment Methods',
                    'route' => 'user.payment-methods.index',
                    'match' => 'user.payment-methods.*',
                ],
            ],
        ],
    ];
@endphp

<aside id="user-sidebar" class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full transform border-r border-sf-border/60 bg-white shadow-xl transition-transform duration-300 ease-in-out dark:border-sf-night-800/50 dark:bg-sf-night-800 md:translate-x-0">
    <!-- Sidebar Header -->
    <div class="flex h-16 items-center justify-between border-b border-sf-border/60 px-6 dark:border-sf-night-800/50">
        <div class="flex items-center gap-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ config('app.name') }}</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">My Account</p>
            </div>
        </div>
        <button id="sidebar-close" class="md:hidden" aria-label="Close sidebar">
            <svg class="h-6 w-6 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4">
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
                        <button type="button" class="menu-toggle w-full flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $isActive || $isOpen ? 'bg-sf-accent-primary/10 text-sf-accent-primary' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900' }}" data-menu="{{ $loop->index }}">
                            <div class="flex items-center gap-3">
                                @include('user.partials.icons.' . $item['icon'])
                                <span>{{ $item['label'] }}</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 {{ $isOpen ? 'rotate-90' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        <ul class="submenu mt-1 space-y-1 overflow-hidden {{ $isOpen ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}" data-submenu="{{ $loop->index }}" data-open="{{ $isOpen ? 'true' : 'false' }}">
                            @foreach ($item['children'] as $child)
                                @php $childActive = request()->routeIs($child['match']); @endphp
                                <li>
                                    <a href="{{ route($child['route']) }}" class="flex items-center gap-3 rounded-lg px-3 py-2 pl-11 text-sm transition-all duration-200 {{ $childActive ? 'bg-sf-accent-primary/15 text-sf-accent-primary font-semibold' : 'text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-sf-night-900' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $childActive ? 'bg-sf-accent-primary' : 'bg-slate-400' }}"></span>
                                        <span>{{ $child['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $isActive ? 'bg-sf-accent-primary/10 text-sf-accent-primary' : 'text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900' }}">
                            @include('user.partials.icons.' . $item['icon'])
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="border-t border-sf-border/60 px-4 py-4 dark:border-sf-night-800/50">
        <div class="flex items-center gap-3 rounded-lg bg-slate-50 p-3 dark:bg-sf-night-900/50">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary">
                <span class="text-sm font-bold text-white">{{ substr(optional(auth()->user())->name ?? 'U', 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">{{ optional(auth()->user())->name ?? 'User' }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ optional(auth()->user())->email ?? '' }}</p>
            </div>
        </div>
        <a href="{{ route('user.profile.show') }}" class="mt-3 flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-sf-night-900">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Account Settings</span>
        </a>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/50 opacity-0 transition-opacity duration-300 md:hidden"></div>

