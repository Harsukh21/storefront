@extends('layouts.user-auth')

@section('content')
<div class="flex min-h-screen items-center justify-center px-6 py-12">
    <div class="user-card w-full max-w-md p-8">
        <h1 class="text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">Two-Factor Verification</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Enter the 6-digit code we emailed to {{ session('two_factor:user:email') ?? 'your inbox' }}.</p>

        @if (session('status'))
            <div class="mt-6 rounded-md border border-sf-accent-primary/40 bg-sf-accent-primary/10 px-4 py-3 text-sm font-semibold text-sf-accent-primary">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('user.two-factor.verify') }}" class="mt-6 space-y-5">
            @csrf
            <div>
                <label for="code" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Authentication Code</label>
                <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" required autofocus class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-center text-lg tracking-widest focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                @error('code')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Verify</button>
        </form>

        <div class="mt-6 flex flex-col gap-2 text-sm">
            <form method="POST" action="{{ route('user.two-factor.resend') }}">
                @csrf
                <button type="submit" class="font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Resend code</button>
            </form>
            <form method="POST" action="{{ route('user.two-factor.cancel') }}">
                @csrf
                <button type="submit" class="font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Use a different account</button>
            </form>
            <a href="{{ route('front.home') }}" class="inline-flex items-center text-sf-accent-primary transition hover:text-sf-accent-secondary">← Back to storefront</a>
        </div>
    </div>
</div>
@endsection
