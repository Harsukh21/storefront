@extends('layouts.admin-panel')

@section('title', $user->name . ' - Users - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.users.index') }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $user->name }}</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">User Details & Management</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Information -->
            <div class="admin-card p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">User Information</h2>
                </div>
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Full Name *</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                required 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            />
                            @error('name')
                                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Email Address *</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                required 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            />
                            @error('email')
                                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Phone Number</label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone', $user->phone) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        @error('phone')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <button 
                            type="submit" 
                            class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                        >
                            Update User
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reset Password -->
            <div class="admin-card p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Reset Password</h2>
                </div>
                <form method="POST" action="{{ route('admin.users.password.update', $user->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">New Password *</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            />
                            @error('password')
                                <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Confirm Password *</label>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            />
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button 
                            type="submit" 
                            class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                        >
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="space-y-6">
            <!-- User Statistics -->
            <div class="admin-card p-6">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Statistics</h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Total Orders</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $orderCount }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                            <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Total Spent</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">${{ number_format($totalSpent, 2) }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                            <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Wishlist Items</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $wishlistCount }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                            <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Saved Addresses</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $addressCount }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                            <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="admin-card p-6">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Account Details</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Email Status</p>
                        <p class="mt-2">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-3 py-1 text-sm font-semibold text-emerald-500">Verified</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-500/10 px-3 py-1 text-sm font-semibold text-amber-500">Unverified</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Registered</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y H:i') }}
                        </p>
                    </div>
                    @if($user->last_login_at)
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Last Login</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y H:i') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="admin-card border-2 border-red-500/50 p-6 dark:border-red-900/50">
                <h2 class="mb-4 text-lg font-bold text-red-600 dark:text-red-400">Danger Zone</h2>
                <p class="mb-6 text-sm text-slate-600 dark:text-slate-300">
                    Deleting a user will permanently remove their account. Users with existing orders cannot be deleted.
                </p>
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="w-full rounded-lg border-2 border-red-500 bg-white px-4 py-2.5 text-sm font-semibold text-red-500 transition-colors hover:bg-red-500 hover:text-white dark:bg-sf-night-800 dark:hover:bg-red-500"
                    >
                        Delete User
                    </button>
                </form>
            </div>
        </aside>
    </div>
</div>
@endsection
