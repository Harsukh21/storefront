@extends('layouts.admin-panel')

@section('title', 'Tax Rates - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Tax Rates</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Manage tax rates by location</p>
        </div>
        <a 
            href="{{ route('admin.tax-rates.create') }}" 
            class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Tax Rate
        </a>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-6 p-6">
        <form method="GET" class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="search" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Search</label>
                <input 
                    name="search" 
                    value="{{ $filters['search'] }}" 
                    placeholder="Search by name, country, or state" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                />
            </div>
            <div class="flex items-end gap-2">
                <button 
                    type="submit" 
                    class="flex-1 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Filter
                </button>
                <a 
                    href="{{ route('admin.tax-rates.index') }}" 
                    class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Tax Rates Table -->
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
                <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Rate</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Priority</th>
                        <th class="px-6 py-4 text-center">Compound</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                    @forelse ($taxRates as $taxRate)
                        <tr class="bg-white/60 text-slate-700 transition-colors hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-slate-100">{{ $taxRate->name }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ number_format($taxRate->rate * 100, 2) }}%</td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                @if($taxRate->country || $taxRate->state || $taxRate->city)
                                    {{ $taxRate->city ? $taxRate->city . ', ' : '' }}{{ $taxRate->state ? $taxRate->state . ', ' : '' }}{{ $taxRate->country ?? '' }}
                                @else
                                    Global
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $taxRate->priority }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $taxRate->compound ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-500/10 text-slate-500 dark:text-slate-400' }}">
                                    {{ $taxRate->compound ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex gap-3">
                                    <a 
                                        href="{{ route('admin.tax-rates.edit', $taxRate->id) }}" 
                                        class="text-sm font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                                    >
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.tax-rates.destroy', $taxRate->id) }}" onsubmit="return confirm('Delete this tax rate?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-red-500 transition-colors hover:text-red-600 dark:text-red-400">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No tax rates yet</h3>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                        <a href="{{ route('admin.tax-rates.create') }}" class="font-semibold text-sf-accent-primary hover:text-sf-accent-secondary">Create your first tax rate</a>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($taxRates->hasPages())
            <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                {{ $taxRates->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
