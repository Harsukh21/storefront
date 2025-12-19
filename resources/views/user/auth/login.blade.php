@extends('layouts.user-auth')

@section('content')
<div class="flex min-h-screen items-center justify-center px-6 py-12">
    <div class="user-card w-full max-w-md p-8">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">User Login</h1>
            <button type="button" class="theme-toggle" aria-label="Toggle theme" onclick="window.UserTheme?.toggle()">
                <span class="sr-only">Toggle theme</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v2m6.364.636-1.414 1.414M21 12h-2m-.636 6.364-1.414-1.414M12 19v2m-6.364-.636 1.414-1.414M5 12H3m.636-6.364 1.414 1.414M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </button>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-md border border-sf-accent-primary/40 bg-sf-accent-primary/10 px-4 py-3 text-sm font-semibold text-sf-accent-primary">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('user.login.attempt') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                @error('email')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200" for="password">Password</label>
                <input id="password" type="password" name="password" required class="mt-1 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
            </div>
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center text-sm text-slate-600 dark:text-slate-200">
                    <input type="checkbox" name="remember" class="rounded border-sf-border text-sf-accent-primary focus:ring-sf-accent-secondary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700" />
                    <span class="ml-2">Remember me</span>
                </label>
                <a href="{{ route('user.password.request') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Forgot?</a>
            </div>
            <button type="submit" class="w-full rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Login</button>
        </form>

        <div class="mt-6 flex flex-col gap-2 text-sm">
            <a href="{{ route('user.register') }}" class="font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Create an account</a>
            <a href="{{ route('front.home') }}" class="inline-flex items-center text-sf-accent-primary transition hover:text-sf-accent-secondary">← Back to storefront</a>
        </div>
    </div>
</div>
@endsection
