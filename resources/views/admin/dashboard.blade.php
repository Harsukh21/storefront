@extends('layouts.admin-panel')

@section('content')
<div class="admin-shell px-6 py-10">
    <div class="w-full admin-card p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">Admin Dashboard</h1>
            <button type="button" class="theme-toggle" aria-label="Toggle theme" onclick="window.AdminTheme?.toggle()">
                <span class="sr-only">Toggle theme</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v2m6.364.636-1.414 1.414M21 12h-2m-.636 6.364-1.414-1.414M12 19v2m-6.364-.636 1.414-1.414M5 12H3m.636-6.364 1.414 1.414M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </button>
        </div>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Welcome back, {{ auth('admin')->user()->name ?? 'Admin' }}!</p>
    </div>
</div>
@endsection
