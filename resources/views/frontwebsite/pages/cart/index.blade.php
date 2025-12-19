@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100">Shopping Cart</h1>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">Review your items before checkout.</p>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-6xl px-6">
        @if($cart && $cart->items && $cart->items->count() > 0)
            <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
                <div class="space-y-4">
                    @foreach($cart->items as $item)
                        <article class="front-card flex flex-col gap-4 p-6 sm:flex-row sm:items-center">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                    <a href="{{ route('front.products.show', $item->product_slug) }}" class="hover:text-sf-accent-primary">{{ $item->product_name }}</a>
                                </h3>
                                @if($item->brand_name)
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $item->brand_name }}</p>
                                @endif
                                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-slate-100">${{ number_format($item->unit_price, 2) }}</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <form method="POST" action="{{ route('front.cart.items.update', $item->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <label for="quantity-{{ $item->id }}" class="sr-only">Quantity</label>
                                    <input type="number" id="quantity-{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" min="1" max="99" class="w-20 rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                                    <button type="submit" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Update</button>
                                </form>
                                <form method="POST" action="{{ route('front.cart.items.destroy', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-red-500 transition hover:text-red-600">Remove</button>
                                </form>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">${{ number_format($item->total_price, 2) }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside class="front-card p-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Order Summary</h2>
                    <div class="mt-6 space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                            <span>Subtotal</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($cart->subtotal, 2) }}</span>
                        </div>
                        @if($cart->discount_total > 0)
                            <div class="flex justify-between text-green-600 dark:text-green-400">
                                <span>Discount</span>
                                <span class="font-semibold">-${{ number_format($cart->discount_total, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                            <span>Tax</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($cart->tax_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                            <span>Shipping</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($cart->shipping_total, 2) }}</span>
                        </div>
                        <div class="border-t border-sf-border pt-3 dark:border-[rgba(114,138,221,0.25)]">
                            <div class="flex justify-between text-lg font-semibold text-slate-900 dark:text-slate-100">
                                <span>Total</span>
                                <span>${{ number_format($cart->grand_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('front.checkout.index') }}" class="mt-6 block w-full rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-center text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Proceed to Checkout</a>
                    <a href="{{ route('front.products') }}" class="mt-3 block w-full text-center text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Continue Shopping</a>
                </aside>
            </div>
        @else
            <div class="front-card px-6 py-12 text-center">
                <p class="text-lg text-slate-600 dark:text-slate-300">Your cart is empty.</p>
                <a href="{{ route('front.products') }}" class="mt-4 inline-block rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Start Shopping</a>
            </div>
        @endif
    </div>
</section>
@endsection
