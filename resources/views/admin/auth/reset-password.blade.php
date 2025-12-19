@extends('layouts.admin')

@section('title', 'Set New Password - ' . config('app.name'))

@section('content')
<div class="w-full max-w-md">
    <!-- Card -->
    <div class="admin-card overflow-hidden p-8 shadow-xl">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary shadow-lg">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Set New Password</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Create a strong password to secure your account</p>
        </div>

        <!-- Reset Form -->
        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                    Email Address
                </label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
                    value="{{ $email ?? old('email') }}" 
                    required 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-3 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                    placeholder="admin@example.com"
                />
                @error('email')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                    New Password
                </label>
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    required 
                    autofocus 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-3 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                    placeholder="••••••••"
                />
                @error('password')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                    Confirm Password
                </label>
                <input 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    required 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-3 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                    placeholder="••••••••"
                />
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] dark:text-sf-night-900"
            >
                Update Password
            </button>
        </form>

        <!-- Footer Links -->
        <div class="mt-8 flex flex-col items-center gap-3 border-t border-sf-border/60 pt-6 dark:border-sf-night-800/50">
            <a 
                href="{{ route('admin.login') }}" 
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition-colors hover:text-sf-accent-primary dark:text-slate-400 dark:hover:text-sf-accent-primary"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Login
            </a>
            <a 
                href="{{ route('front.home') }}" 
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition-colors hover:text-sf-accent-primary dark:text-slate-400 dark:hover:text-sf-accent-primary"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Storefront
            </a>
        </div>
    </div>
@endsection
