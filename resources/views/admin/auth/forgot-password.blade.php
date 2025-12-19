@extends('layouts.admin')

@section('title', 'Reset Password - ' . config('app.name'))

@section('content')
<div class="w-full max-w-md">
    <!-- Card -->
    <div class="admin-card overflow-hidden p-8 shadow-xl">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary shadow-lg">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Reset Password</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Enter your email to receive a password reset link</p>
        </div>

        <!-- Status Message -->
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-sf-accent-primary/40 bg-sf-accent-primary/10 px-4 py-3 text-sm font-semibold text-sf-accent-primary">
                {{ session('status') }}
            </div>
        @endif

        <!-- Reset Form -->
        <form method="POST" action="{{ route('admin.password.email') }}" class="space-y-6">
            @csrf
            
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                    Email Address
                </label>
                <input 
                    id="email" 
                    name="email" 
                    type="email" 
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

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] dark:text-sf-night-900"
            >
                Send Reset Link
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
