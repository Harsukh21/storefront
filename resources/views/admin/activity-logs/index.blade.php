@extends('layouts.admin-panel')

@section('title', 'Activity Logs - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Activity Logs</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">View admin activity history</p>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-6 p-6">
        <form method="GET" class="grid gap-4 md:grid-cols-4">
            <div>
                <label for="action" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Action</label>
                <input 
                    name="action" 
                    value="{{ $filters['action'] }}" 
                    placeholder="Search by action" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                />
            </div>
            <div>
                <label for="admin_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Admin</label>
                <select 
                    name="admin_id" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                >
                    <option value="">All Admins</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" @selected($filters['admin_id'] == $admin->id)>{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Date From</label>
                <input 
                    name="date_from" 
                    type="date" 
                    value="{{ $filters['date_from'] }}" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                />
            </div>
            <div>
                <label for="date_to" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Date To</label>
                <input 
                    name="date_to" 
                    type="date" 
                    value="{{ $filters['date_to'] }}" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                />
            </div>
            <div class="md:col-span-4 flex gap-2">
                <button 
                    type="submit" 
                    class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Filter
                </button>
                <a 
                    href="{{ route('admin.activity-logs.index') }}" 
                    class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Activity Logs Table -->
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
                <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">Admin</th>
                        <th class="px-6 py-4">Action</th>
                        <th class="px-6 py-4">Target</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                    @forelse ($logs as $log)
                        <tr class="bg-white/60 text-slate-700 transition-colors hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $log->admin_name ?? 'System' }}</p>
                                @if($log->admin_email)
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $log->admin_email }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full bg-sf-accent-primary/10 px-2.5 py-1 text-xs font-semibold text-sf-accent-primary">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">
                                @if($log->auditable_type && $log->auditable_id)
                                    <span class="font-mono text-xs">{{ $log->auditable_type }} #{{ $log->auditable_id }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No activity logs found</h3>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Try adjusting your filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
