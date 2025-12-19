@extends('layouts.admin-panel')

@section('title', 'Notifications - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Notifications</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Manage your notifications</p>
        </div>
        @if($unreadCount > 0)
            <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" class="inline">
                @csrf
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Mark All Read ({{ $unreadCount }})
                </button>
            </form>
        @endif
    </div>

    <!-- Filters -->
    <div class="admin-card mb-6 p-6">
        <form method="GET" class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="type" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Type</label>
                <select 
                    name="type" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                >
                    <option value="">All Types</option>
                    <option value="order" @selected($filters['type'] === 'order')>Orders</option>
                    <option value="payment" @selected($filters['type'] === 'payment')>Payments</option>
                    <option value="review" @selected($filters['type'] === 'review')>Reviews</option>
                    <option value="question" @selected($filters['type'] === 'question')>Questions</option>
                </select>
            </div>
            <div class="flex items-end">
                <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                    <input 
                        type="checkbox" 
                        id="unread_only" 
                        name="unread_only" 
                        value="1" 
                        {{ $filters['unread_only'] ? 'checked' : '' }} 
                        class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)]" 
                    />
                    <label for="unread_only" class="text-sm font-medium text-slate-700 dark:text-slate-200">Unread only</label>
                </div>
            </div>
            <div class="flex items-end gap-2">
                <button 
                    type="submit" 
                    class="flex-1 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Filter
                </button>
                <a 
                    href="{{ route('admin.notifications.index') }}" 
                    class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Notifications List -->
    <div class="admin-card p-6">
        <div class="space-y-4">
            @forelse ($notifications as $notification)
                <div class="flex items-start gap-4 rounded-lg border border-sf-border/60 p-4 transition-colors {{ !$notification->read_at ? 'bg-sf-accent-primary/5 dark:bg-sf-accent-primary/10' : 'hover:bg-slate-50 dark:hover:bg-sf-night-900/50' }} dark:border-[rgba(114,138,221,0.25)]">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-slate-900 dark:text-slate-100">{{ ucfirst($notification->type) }}</h3>
                            @if(!$notification->read_at)
                                <span class="h-2 w-2 rounded-full bg-sf-accent-primary"></span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            @php
                                $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                                echo $data['message'] ?? 'No message';
                            @endphp
                        </p>
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex gap-3">
                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}" class="inline">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="text-sm font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                                >
                                    Mark Read
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}" onsubmit="return confirm('Delete this notification?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit" 
                                class="text-sm font-semibold text-red-500 transition-colors hover:text-red-600 dark:text-red-400"
                            >
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No notifications found</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Try adjusting your filter criteria.</p>
                </div>
            @endforelse
        </div>
        @if($notifications->hasPages())
            <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
