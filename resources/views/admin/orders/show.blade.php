@extends('layouts.admin-panel')

@section('title', 'Order ' . $order->order_number . ' - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.orders.index') }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Order {{ $order->order_number }}</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Placed on {{ \Carbon\Carbon::parse($order->placed_at ?? $order->created_at)->format('M d, Y H:i') }}
            </p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
        <!-- Main Content -->
        <div class="space-y-6">
            <!-- Order Status Update -->
            <div class="admin-card p-6">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Update Order Status</h2>
                <form method="POST" action="{{ route('admin.orders.update-status', $order->order_number) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-6 sm:grid-cols-3">
                        <div>
                            <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Order Status</label>
                            <select 
                                id="status" 
                                name="status" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">No change</option>
                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                <option value="processing" @selected($order->status === 'processing')>Processing</option>
                                <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                                <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                                <option value="refunded" @selected($order->status === 'refunded')>Refunded</option>
                            </select>
                        </div>
                        <div>
                            <label for="payment_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Payment Status</label>
                            <select 
                                id="payment_status" 
                                name="payment_status" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">No change</option>
                                <option value="pending" @selected($order->payment_status === 'pending')>Pending</option>
                                <option value="paid" @selected($order->payment_status === 'paid')>Paid</option>
                                <option value="failed" @selected($order->payment_status === 'failed')>Failed</option>
                                <option value="refunded" @selected($order->payment_status === 'refunded')>Refunded</option>
                            </select>
                        </div>
                        <div>
                            <label for="fulfillment_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Fulfillment</label>
                            <select 
                                id="fulfillment_status" 
                                name="fulfillment_status" 
                                class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            >
                                <option value="">No change</option>
                                <option value="unfulfilled" @selected($order->fulfillment_status === 'unfulfilled')>Unfulfilled</option>
                                <option value="partially_fulfilled" @selected($order->fulfillment_status === 'partially_fulfilled')>Partially Fulfilled</option>
                                <option value="fulfilled" @selected($order->fulfillment_status === 'fulfilled')>Fulfilled</option>
                                <option value="cancelled" @selected($order->fulfillment_status === 'cancelled')>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Order Notes</label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            rows="3" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            placeholder="Add internal notes..."
                        >{{ old('notes', $order->notes) }}</textarea>
                    </div>
                    <div class="flex items-center gap-3">
                        <button 
                            type="submit" 
                            class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                        >
                            Update Order
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Items -->
            <div class="admin-card p-6">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Order Items</h2>
                <div class="space-y-4">
                    @foreach($orderItems as $item)
                        <div class="flex items-center justify-between rounded-lg border border-sf-border/60 p-4 transition-colors hover:bg-slate-50 dark:border-[rgba(114,138,221,0.25)] dark:hover:bg-sf-night-900/50">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->name_snapshot }}</p>
                                @if($item->sku_snapshot)
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">SKU: {{ $item->sku_snapshot }}</p>
                                @endif
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                    Quantity: {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}
                                </p>
                            </div>
                            <p class="ml-4 text-lg font-bold text-slate-900 dark:text-slate-100">${{ number_format($item->total_price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipments -->
            <div class="admin-card p-6">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Shipments</h2>
                </div>
                @if($shipments->count() > 0)
                    <div class="mb-6 space-y-4">
                        @foreach($shipments as $shipment)
                            <div class="rounded-lg border border-sf-border/60 p-4 dark:border-[rgba(114,138,221,0.25)]">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $shipment->shipment_number }}</p>
                                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                            Status: <span class="font-semibold capitalize">{{ $shipment->status }}</span>
                                        </p>
                                        @if($shipment->carrier)
                                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Carrier: {{ $shipment->carrier }}</p>
                                        @endif
                                        @if($shipment->tracking_number)
                                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Tracking: <span class="font-mono">{{ $shipment->tracking_number }}</span></p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('admin.shipments.update', $shipment->id) }}" class="ml-4">
                                        @csrf
                                        @method('PUT')
                                        <select 
                                            name="status" 
                                            onchange="this.form.submit()" 
                                            class="rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 text-xs transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                                        >
                                            <option value="pending" @selected($shipment->status === 'pending')>Pending</option>
                                            <option value="shipped" @selected($shipment->status === 'shipped')>Shipped</option>
                                            <option value="in_transit" @selected($shipment->status === 'in_transit')>In Transit</option>
                                            <option value="delivered" @selected($shipment->status === 'delivered')>Delivered</option>
                                            <option value="returned" @selected($shipment->status === 'returned')>Returned</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">No shipments created yet.</p>
                @endif

                <div class="border-t border-sf-border/60 pt-6 dark:border-[rgba(114,138,221,0.25)]">
                    <h3 class="mb-4 text-sm font-bold text-slate-900 dark:text-slate-100">Create New Shipment</h3>
                    <form method="POST" action="{{ route('admin.shipments.store', $order->order_number) }}" class="space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label for="carrier" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Carrier *</label>
                                <input 
                                    type="text" 
                                    id="carrier" 
                                    name="carrier" 
                                    required 
                                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                                    placeholder="UPS"
                                />
                            </div>
                            <div>
                                <label for="service" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Service</label>
                                <input 
                                    type="text" 
                                    id="service" 
                                    name="service" 
                                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                                    placeholder="Ground"
                                />
                            </div>
                            <div>
                                <label for="tracking_number" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Tracking # *</label>
                                <input 
                                    type="text" 
                                    id="tracking_number" 
                                    name="tracking_number" 
                                    required 
                                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                                    placeholder="1Z999AA10123456784"
                                />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Items to Ship</label>
                            <div class="space-y-2 rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                                @foreach($orderItems as $item)
                                    <div class="flex items-center gap-3">
                                        <input 
                                            type="checkbox" 
                                            name="items[{{ $loop->index }}][order_item_id]" 
                                            value="{{ $item->id }}" 
                                            class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)]" 
                                        />
                                        <span class="flex-1 text-sm text-slate-700 dark:text-slate-200">{{ $item->name_snapshot }} (Qty: {{ $item->quantity }})</span>
                                        <input 
                                            type="number" 
                                            name="items[{{ $loop->index }}][quantity]" 
                                            value="{{ $item->quantity }}" 
                                            min="1" 
                                            max="{{ $item->quantity }}" 
                                            class="w-20 rounded-lg border border-sf-border/60 bg-white px-2 py-1 text-xs transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                                        />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button 
                            type="submit" 
                            class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                        >
                            Create Shipment
                        </button>
                    </form>
                </div>
            </div>

            <!-- Refunds -->
            @if($payment)
                <div class="admin-card p-6">
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Refunds</h2>
                    </div>
                    @if($refunds->count() > 0)
                        <div class="mb-6 space-y-4">
                            @foreach($refunds as $refund)
                                <div class="rounded-lg border border-sf-border/60 p-4 dark:border-[rgba(114,138,221,0.25)]">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <p class="text-lg font-bold text-slate-900 dark:text-slate-100">${{ number_format($refund->amount, 2) }}</p>
                                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                                                Status: <span class="font-semibold capitalize">{{ $refund->status }}</span>
                                            </p>
                                            @if($refund->reason)
                                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Reason: {{ $refund->reason }}</p>
                                            @endif
                                        </div>
                                        <form method="POST" action="{{ route('admin.refunds.update', $refund->id) }}" class="ml-4">
                                            @csrf
                                            @method('PUT')
                                            <select 
                                                name="status" 
                                                onchange="this.form.submit()" 
                                                class="rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 text-xs transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                                            >
                                                <option value="pending" @selected($refund->status === 'pending')>Pending</option>
                                                <option value="processed" @selected($refund->status === 'processed')>Processed</option>
                                                <option value="failed" @selected($refund->status === 'failed')>Failed</option>
                                                <option value="cancelled" @selected($refund->status === 'cancelled')>Cancelled</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @php
                        $totalRefunded = $refunds->where('status', '!=', 'cancelled')->sum('amount');
                        $remainingAmount = $payment->amount - $totalRefunded;
                    @endphp

                    @if($remainingAmount > 0)
                        <div class="border-t border-sf-border/60 pt-6 dark:border-[rgba(114,138,221,0.25)]">
                            <h3 class="mb-4 text-sm font-bold text-slate-900 dark:text-slate-100">Create Refund</h3>
                            <p class="mb-4 text-sm text-slate-600 dark:text-slate-300">Remaining refundable: <span class="font-semibold text-sf-accent-primary">${{ number_format($remainingAmount, 2) }}</span></p>
                            <form method="POST" action="{{ route('admin.refunds.store', $order->order_number) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="refund_amount" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Amount *</label>
                                    <input 
                                        type="number" 
                                        id="refund_amount" 
                                        name="amount" 
                                        step="0.01" 
                                        min="0.01" 
                                        max="{{ $remainingAmount }}" 
                                        required 
                                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                                        placeholder="0.00"
                                    />
                                </div>
                                <div>
                                    <label for="refund_reason" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Reason</label>
                                    <textarea 
                                        id="refund_reason" 
                                        name="reason" 
                                        rows="3" 
                                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                                        placeholder="Reason for refund..."
                                    ></textarea>
                                </div>
                                <button 
                                    type="submit" 
                                    class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                                >
                                    Create Refund
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="space-y-6">
            <!-- Order Summary -->
            <div class="admin-card p-6">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Order Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span>Subtotal</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount_total > 0)
                        <div class="flex justify-between text-sm text-emerald-600 dark:text-emerald-400">
                            <span>Discount</span>
                            <span class="font-semibold">-${{ number_format($order->discount_total, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span>Tax</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($order->tax_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span>Shipping</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($order->shipping_total, 2) }}</span>
                    </div>
                    <div class="border-t border-sf-border/60 pt-3 dark:border-[rgba(114,138,221,0.25)]">
                        <div class="flex justify-between text-lg font-bold text-slate-900 dark:text-slate-100">
                            <span>Total</span>
                            <span>${{ number_format($order->grand_total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="admin-card p-6">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Customer</h2>
                @if($order->user_name)
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $order->user_name }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-300">{{ $order->user_email }}</p>
                        @if($order->user_phone)
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $order->user_phone }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">Guest order</p>
                @endif
            </div>

            <!-- Shipping Address -->
            @if($shippingAddress)
                <div class="admin-card p-6">
                    <h2 class="mb-4 text-sm font-bold text-slate-900 dark:text-slate-100">Shipping Address</h2>
                    <div class="space-y-1 text-sm text-slate-600 dark:text-slate-300">
                        <p>{{ $shippingAddress->recipient_name }}</p>
                        <p>{{ $shippingAddress->line1 }}</p>
                        @if($shippingAddress->line2)
                            <p>{{ $shippingAddress->line2 }}</p>
                        @endif
                        <p>{{ $shippingAddress->city }}, {{ $shippingAddress->state }} {{ $shippingAddress->postal_code }}</p>
                        <p>{{ $shippingAddress->country }}</p>
                    </div>
                </div>
            @endif

            <!-- Billing Address -->
            @if($billingAddress)
                <div class="admin-card p-6">
                    <h2 class="mb-4 text-sm font-bold text-slate-900 dark:text-slate-100">Billing Address</h2>
                    <div class="space-y-1 text-sm text-slate-600 dark:text-slate-300">
                        <p>{{ $billingAddress->recipient_name }}</p>
                        <p>{{ $billingAddress->line1 }}</p>
                        @if($billingAddress->line2)
                            <p>{{ $billingAddress->line2 }}</p>
                        @endif
                        <p>{{ $billingAddress->city }}, {{ $billingAddress->state }} {{ $billingAddress->postal_code }}</p>
                        <p>{{ $billingAddress->country }}</p>
                    </div>
                </div>
            @endif

            <!-- Payment Info -->
            @if($payment)
                <div class="admin-card p-6">
                    <h2 class="mb-4 text-sm font-bold text-slate-900 dark:text-slate-100">Payment</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-300">Provider</span>
                            <span class="font-semibold capitalize text-slate-900 dark:text-slate-100">{{ $payment->provider }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-300">Amount</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-600 dark:text-slate-300">Status</span>
                            <span class="font-semibold capitalize text-slate-900 dark:text-slate-100">{{ $payment->status }}</span>
                        </div>
                        @if($payment->transaction_id)
                            <div class="mt-3 border-t border-sf-border/60 pt-3 dark:border-[rgba(114,138,221,0.25)]">
                                <p class="text-xs text-slate-500 dark:text-slate-400">Transaction ID</p>
                                <p class="mt-1 font-mono text-xs text-slate-900 dark:text-slate-100">{{ $payment->transaction_id }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
