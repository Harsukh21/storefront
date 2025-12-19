@extends('layouts.user')

@section('content')
<div class="user-shell px-6 py-10">
    <div class="mx-auto max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('user.orders.index') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">← Back to Orders</a>
            <h1 class="mt-4 text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">Order {{ $order->order_number }}</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                Placed on {{ \Carbon\Carbon::parse($order->placed_at ?? $order->created_at)->format('M d, Y H:i') }}
            </p>
        </div>

        <div class="user-card p-6 mb-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4">Order Status</h2>
            <div class="grid gap-4 sm:grid-cols-3 text-sm">
                <div>
                    <p class="text-slate-500 dark:text-slate-400">Order Status</p>
                    <p class="mt-1 font-semibold text-slate-900 dark:text-slate-100 capitalize">{{ $order->status }}</p>
                </div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400">Payment Status</p>
                    <p class="mt-1 font-semibold text-slate-900 dark:text-slate-100 capitalize">{{ $order->payment_status }}</p>
                </div>
                <div>
                    <p class="text-slate-500 dark:text-slate-400">Fulfillment Status</p>
                    <p class="mt-1 font-semibold text-slate-900 dark:text-slate-100 capitalize">{{ $order->fulfillment_status }}</p>
                </div>
            </div>
        </div>

        @if($shippingAddress || $billingAddress)
            <div class="grid gap-6 md:grid-cols-2 mb-6">
                @if($shippingAddress)
                    <div class="user-card p-6">
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
                    <div class="user-card p-6">
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
        @endif

        <div class="user-card p-6 mb-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Order Items</h3>
            <div class="space-y-4">
                @foreach($orderItems as $item)
                    <div class="flex items-center justify-between border-b border-sf-border pb-4 dark:border-[rgba(114,138,221,0.25)]">
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->name_snapshot }}</p>
                            @if($item->sku_snapshot)
                                <p class="text-xs text-slate-500 dark:text-slate-400">SKU: {{ $item->sku_snapshot }}</p>
                            @endif
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Quantity: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}</p>
                            @if($item->product_slug)
                                <a href="{{ route('front.products.show', $item->product_slug) }}" class="mt-2 inline-block text-xs font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View Product →</a>
                            @endif
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
                @if($order->discount_total > 0)
                    <div class="flex justify-between text-green-600 dark:text-green-400">
                        <span>Discount</span>
                        <span class="font-semibold">-${{ number_format($order->discount_total, 2) }}</span>
                    </div>
                @endif
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

        @if($shipments && $shipments->count() > 0)
            <div class="user-card p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Shipments</h3>
                <div class="space-y-4">
                    @foreach($shipments as $shipment)
                        <div class="border-b border-sf-border pb-4 dark:border-[rgba(114,138,221,0.25)]">
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $shipment->shipment_number }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Status: <span class="capitalize">{{ $shipment->status }}</span></p>
                            @if($shipment->tracking_number)
                                <p class="text-sm text-slate-500 dark:text-slate-400">Tracking: {{ $shipment->tracking_number }}</p>
                            @endif
                            @if($shipment->carrier)
                                <p class="text-sm text-slate-500 dark:text-slate-400">Carrier: {{ $shipment->carrier }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
