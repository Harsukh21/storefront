@extends('layouts.admin-panel')

@section('title', $admin->name . ' - Admins - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.admins.index') }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $admin->name }}</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Admin Details & Management</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Admin Information -->
            <div class="admin-card p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Admin Information</h2>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary shadow-md">
                            <span class="text-xl font-bold text-white">{{ substr($admin->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Name</p>
                            <p class="mt-1 text-lg font-bold text-slate-900 dark:text-slate-100">{{ $admin->name }}</p>
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Email</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $admin->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Role</p>
                            <p class="mt-2">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold capitalize {{ $admin->role === 'admin' ? 'bg-purple-500/10 text-purple-500' : 'bg-blue-500/10 text-blue-500' }}">
                                    {{ $admin->role }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Account Created</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ \Carbon\Carbon::parse($admin->created_at)->format('M d, Y H:i') }}
                            </p>
                        </div>
                        @if($admin->last_login_at)
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Last Login</p>
                                <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                    {{ \Carbon\Carbon::parse($admin->last_login_at)->format('M d, Y H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                    @if($admin->id != auth('admin')->id())
                        <div class="pt-4">
                            <a 
                                href="{{ route('admin.admins.edit', $admin->id) }}" 
                                class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Admin
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reset Password -->
            <div class="admin-card p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Reset Password</h2>
                </div>
                <form method="POST" action="{{ route('admin.admins.password.update', $admin->id) }}" class="space-y-6">
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
                                placeholder="••••••••"
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
                                placeholder="••••••••"
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
        <aside>
            <!-- Danger Zone -->
            @if($admin->id != auth('admin')->id())
                <div class="admin-card border-2 border-red-500/50 p-6 dark:border-red-900/50">
                    <h2 class="mb-4 text-lg font-bold text-red-600 dark:text-red-400">Danger Zone</h2>
                    <p class="mb-6 text-sm text-slate-600 dark:text-slate-300">
                        Deleting an admin will permanently remove their account and access to the admin panel.
                    </p>
                    <form method="POST" action="{{ route('admin.admins.destroy', $admin->id) }}" onsubmit="return confirm('Are you sure you want to delete this admin? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="w-full rounded-lg border-2 border-red-500 bg-white px-4 py-2.5 text-sm font-semibold text-red-500 transition-colors hover:bg-red-500 hover:text-white dark:bg-sf-night-800 dark:hover:bg-red-500"
                        >
                            Delete Admin
                        </button>
                    </form>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
