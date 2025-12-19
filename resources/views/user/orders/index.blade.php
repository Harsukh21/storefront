@extends('layouts.user')

@section('content')
<div class="user-shell px-6 py-10">
    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">Order History</h1>
            <p class="text-sm text-slate-600 dark:text-slate-300">View all your past orders.</p>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <article class="user-card p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-sf-accent-primary">{{ $order->order_number }}</h3>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    Placed on {{ \Carbon\Carbon::parse($order->placed_at ?? $order->created_at)->format('M d, Y H:i') }}
                                </p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-full bg-sf-accent-primary/10 px-3 py-1 text-xs font-semibold text-sf-accent-primary capitalize">{{ $order->status }}</span>
                                    <span class="inline-flex items-center rounded-full bg-sf-accent-secondary/10 px-3 py-1 text-xs font-semibold text-sf-accent-secondary capitalize">{{ $order->payment_status }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">${{ number_format($order->grand_total, 2) }}</p>
                                <a href="{{ route('user.orders.show', $order->order_number) }}" class="mt-2 inline-block text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View Details →</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-6">{{ $orders->links() }}</div>
        @else
            <div class="user-card px-6 py-12 text-center">
                <p class="text-lg text-slate-600 dark:text-slate-300">You haven't placed any orders yet.</p>
                <a href="{{ route('front.products') }}" class="mt-4 inline-block rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Start Shopping</a>
            </div>
        @endif
    </div>
</div>
@endsection
