@extends('layouts.admin-panel')

@section('title', 'Payment Details - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.payments.index') }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Payment Details</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Transaction ID: {{ $payment->transaction_id ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="admin-card p-8">
        <div class="mb-8 grid gap-6 md:grid-cols-2">
            <div class="rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Order Number</p>
                <a 
                    href="{{ route('admin.orders.show', $payment->order_number) }}" 
                    class="mt-2 inline-block text-lg font-bold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                >
                    {{ $payment->order_number }}
                </a>
            </div>
            <div class="rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Amount</p>
                <p class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">
                    {{ $payment->currency }} {{ number_format($payment->amount, 2) }}
                </p>
            </div>
            <div class="rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Provider</p>
                <p class="mt-2 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ ucfirst($payment->provider) }}</p>
            </div>
            <div class="rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Status</p>
                <p class="mt-2">
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold 
                        {{ $payment->status === 'completed' ? 'bg-emerald-500/10 text-emerald-500' : '' }}
                        {{ $payment->status === 'pending' ? 'bg-amber-500/10 text-amber-500' : '' }}
                        {{ $payment->status === 'failed' ? 'bg-red-500/10 text-red-500' : '' }}
                        {{ $payment->status === 'refunded' ? 'bg-blue-500/10 text-blue-500' : '' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </p>
            </div>
            <div class="rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Processed At</p>
                <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ $payment->processed_at ? \Carbon\Carbon::parse($payment->processed_at)->format('M d, Y H:i:s') : 'Not processed' }}
                </p>
            </div>
            <div class="rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Created At</p>
                <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">
                    {{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y H:i:s') }}
                </p>
            </div>
        </div>

        @if($refunds->count() > 0)
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Refunds</h2>
                <div class="space-y-4">
                    @foreach($refunds as $refund)
                        <div class="rounded-lg border border-sf-border/60 p-4 dark:border-[rgba(114,138,221,0.25)]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-bold text-slate-900 dark:text-slate-100">
                                        {{ $payment->currency }} {{ number_format($refund->amount, 2) }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($refund->created_at)->format('M d, Y H:i') }}
                                    </p>
                                </div>
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold 
                                    {{ $refund->status === 'completed' || $refund->status === 'processed' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500' }}">
                                    {{ ucfirst($refund->status) }}
                                </span>
                            </div>
                            @if($refund->reason)
                                <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $refund->reason }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
