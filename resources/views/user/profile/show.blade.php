@extends('layouts.user')

@section('content')
<div class="user-shell px-6 py-10">
    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">Profile Settings</h1>
            <p class="text-sm text-slate-600 dark:text-slate-300">Manage your account information</p>
        </div>

        <div class="user-card p-6 mb-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Personal Information</h2>
            <form method="POST" action="{{ route('user.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-md text-sm" />
                    @error('name')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-md text-sm" />
                    @error('email')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-md text-sm" />
                    @error('phone')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Update Profile</button>
            </form>
        </div>

        <div class="user-card p-6">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Change Password</h2>
            <form method="POST" action="{{ route('user.profile.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required class="w-full rounded-md text-sm" />
                    @error('current_password')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">New Password</label>
                    <input type="password" id="password" name="password" required class="w-full rounded-md text-sm" />
                    @error('password')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full rounded-md text-sm" />
                </div>
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
