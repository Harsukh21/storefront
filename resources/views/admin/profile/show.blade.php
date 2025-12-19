@extends('layouts.admin-panel')

@section('title', 'Profile Settings - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Profile Settings</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Manage your admin account information</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Personal Information -->
        <div class="admin-card p-8">
            <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Personal Information</h2>
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Full Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $admin->name) }}" 
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
                        value="{{ old('email', $admin->email) }}" 
                        required 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Role</label>
                    <input 
                        type="text" 
                        value="{{ ucfirst($admin->role ?? 'manager') }}" 
                        disabled 
                        class="w-full rounded-lg border border-sf-border/60 bg-slate-100 px-4 py-2.5 text-sm text-slate-500 dark:bg-sf-night-800 dark:text-slate-400" 
                    />
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Role cannot be changed from this interface.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button 
                        type="submit" 
                        class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                    >
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="admin-card p-8">
            <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Change Password</h2>
            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Current Password *</label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        required 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        placeholder="••••••••"
                    />
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
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
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Confirm New Password *</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        required 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        placeholder="••••••••"
                    />
                </div>
                <div class="flex items-center gap-3">
                    <button 
                        type="submit" 
                        class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                    >
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Account Information -->
    <div class="admin-card mt-6 p-8">
        <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Account Information</h2>
        <div class="grid gap-6 sm:grid-cols-2">
            <div class="rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Account Created</p>
                <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                    {{ \Carbon\Carbon::parse($admin->created_at)->format('M d, Y') }}
                </p>
            </div>
            <div class="rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Last Login</p>
                <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                    @if($admin->last_login_at)
                        {{ \Carbon\Carbon::parse($admin->last_login_at)->format('M d, Y H:i') }}
                    @else
                        Never
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
