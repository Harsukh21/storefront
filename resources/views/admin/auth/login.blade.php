@extends('layouts.admin')

@section('title', 'Admin Login - ' . config('app.name'))

@section('content')
<div class="w-full max-w-md">
    <!-- Card -->
    <div class="admin-card overflow-hidden p-8 shadow-xl">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary shadow-lg">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Admin Login</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Sign in to access the admin panel</p>
        </div>

        <!-- Status Message -->
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-sf-accent-primary/40 bg-sf-accent-primary/10 px-4 py-3 text-sm font-semibold text-sf-accent-primary">
                {{ session('status') }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ route('admin.login.attempt') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                    Email Address
                </label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
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
                    Password
                </label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-3 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                    placeholder="••••••••"
                />
                @error('password')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center text-sm text-slate-600 dark:text-slate-300">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700" 
                    />
                    <span class="ml-2">Remember me</span>
                </label>
                <a 
                    href="{{ route('admin.password.request') }}" 
                    class="text-sm font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                >
                    Forgot password?
                </a>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] dark:text-sf-night-900"
            >
                Sign In
            </button>
        </form>

        <!-- Footer Links -->
        <div class="mt-8 flex flex-col items-center gap-3 border-t border-sf-border/60 pt-6 dark:border-sf-night-800/50">
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
