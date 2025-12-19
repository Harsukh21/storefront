@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20 text-center">
        <div class="mx-auto inline-flex h-16 w-16 items-center justify-center rounded-full bg-green-500/20">
            <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="mt-6 text-4xl font-bold text-slate-900 dark:text-slate-100">Order Confirmed!</h1>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">Thank you for your purchase. Your order number is <strong class="text-sf-accent-primary">{{ $order->order_number }}</strong>.</p>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-4xl px-6">
        <div class="front-card p-6 mb-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Order Details</h2>
            <div class="grid gap-4 sm:grid-cols-2 text-sm">
                <div>
                    <p class="text-slate-500 dark:text-slate-400">Order Number</p>
                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400">Placed On</p>
                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ \Carbon\Carbon::parse($order->placed_at)->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400">Status</p>
                    <p class="font-semibold text-slate-900 dark:text-slate-100 capitalize">{{ $order->status }}</p>
                </div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400">Payment Status</p>
                    <p class="font-semibold text-slate-900 dark:text-slate-100 capitalize">{{ $order->payment_status }}</p>
                </div>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 mb-6">
            @if($shippingAddress)
                <div class="front-card p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Shipping Address</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $shippingAddress->recipient_name }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $shippingAddress->line1 }}</p>
                    @if($shippingAddress->line2)
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $shippingAddress->line2 }}</p>
                    @endif
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->postal_code }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $shippingAddress->country }}</p>
                </div>
            @endif
            @if($billingAddress)
                <div class="front-card p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Billing Address</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $billingAddress->recipient_name }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $billingAddress->line1 }}</p>
                    @if($billingAddress->line2)
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $billingAddress->line2 }}</p>
                    @endif
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->postal_code }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $billingAddress->country }}</p>
                </div>
            @endif
        </div>

        <div class="front-card p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Items Ordered</h3>
            <div class="space-y-4">
                @foreach($orderItems as $item)
                    <div class="flex items-center justify-between border-b border-sf-border pb-4 dark:border-[rgba(114,138,221,0.25)]">
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->name_snapshot }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Quantity: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                        </div>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($item->total_price, 2) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6 space-y-2 border-t border-sf-border pt-4 text-sm dark:border-[rgba(114,138,221,0.25)]">
                <div class="flex justify-between text-slate-600 dark:text-slate-300">
                    <span>Subtotal</span>
                    <span class="font-semibold">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600 dark:text-slate-300">
                    <span>Tax</span>
                    <span class="font-semibold">${{ number_format($order->tax_total, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-600 dark:text-slate-300">
                    <span>Shipping</span>
                    <span class="font-semibold">${{ number_format($order->shipping_total, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg font-semibold text-slate-900 dark:text-slate-100 pt-2 border-t border-sf-border dark:border-[rgba(114,138,221,0.25)]">
                    <span>Total</span>
                    <span>${{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-4 sm:flex-row">
            @auth
                <a href="{{ route('user.orders.show', $order->order_number) }}" class="rounded-full border border-sf-accent-primary px-6 py-3 text-center text-sm font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/10">View Order Details</a>
            @endauth
            <a href="{{ route('front.products') }}" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-center text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Continue Shopping</a>
        </div>
    </div>
</section>
@endsection
