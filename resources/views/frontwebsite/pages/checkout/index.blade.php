@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100">Checkout</h1>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">Complete your order details.</p>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-6xl px-6">
        <form method="POST" action="{{ route('front.checkout.store') }}" class="grid gap-8 lg:grid-cols-[2fr,1fr]">
            @csrf
            <div class="space-y-8">
                <div class="front-card p-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Shipping Address</h2>
                    @if(Auth::check() && $addresses->count() > 0)
                        <div class="mb-6 space-y-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Use saved address</label>
                            <select id="saved-shipping" class="w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100">
                                <option value="">Enter new address</option>
                                @foreach($addresses as $addr)
                                    <option value="{{ $addr->id }}">{{ $addr->label ?? $addr->recipient_name }} - {{ $addr->line1 }}, {{ $addr->city }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="space-y-4">
                        <div>
                            <label for="shipping_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Full Name *</label>
                            <input type="text" id="shipping_name" name="shipping_name" value="{{ old('shipping_name') }}" required class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                            @error('shipping_name')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="shipping_phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Phone</label>
                            <input type="text" id="shipping_phone" name="shipping_phone" value="{{ old('shipping_phone') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                        </div>
                        <div>
                            <label for="shipping_line1" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Address Line 1 *</label>
                            <input type="text" id="shipping_line1" name="shipping_line1" value="{{ old('shipping_line1') }}" required class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                            @error('shipping_line1')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="shipping_line2" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Address Line 2</label>
                            <input type="text" id="shipping_line2" name="shipping_line2" value="{{ old('shipping_line2') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="shipping_city" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">City *</label>
                                <input type="text" id="shipping_city" name="shipping_city" value="{{ old('shipping_city') }}" required class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                                @error('shipping_city')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="shipping_state" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">State/Province</label>
                                <input type="text" id="shipping_state" name="shipping_state" value="{{ old('shipping_state') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="shipping_postal_code" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Postal Code</label>
                                <input type="text" id="shipping_postal_code" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                            </div>
                            <div>
                                <label for="shipping_country" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Country *</label>
                                <input type="text" id="shipping_country" name="shipping_country" value="{{ old('shipping_country', 'US') }}" maxlength="2" required class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                                @error('shipping_country')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="front-card p-6">
                    <div class="mb-6 flex items-center gap-2">
                        <input type="checkbox" id="billing_same_as_shipping" name="billing_same_as_shipping" value="1" @checked(old('billing_same_as_shipping')) class="rounded border-sf-border text-sf-accent-primary focus:ring-sf-accent-secondary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700" />
                        <label for="billing_same_as_shipping" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Billing address same as shipping</label>
                    </div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Billing Address</h2>
                    <div id="billing-fields" class="space-y-4">
                        <div>
                            <label for="billing_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Full Name *</label>
                            <input type="text" id="billing_name" name="billing_name" value="{{ old('billing_name') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                            @error('billing_name')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="billing_phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Phone</label>
                            <input type="text" id="billing_phone" name="billing_phone" value="{{ old('billing_phone') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                        </div>
                        <div>
                            <label for="billing_line1" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Address Line 1 *</label>
                            <input type="text" id="billing_line1" name="billing_line1" value="{{ old('billing_line1') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                            @error('billing_line1')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="billing_line2" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Address Line 2</label>
                            <input type="text" id="billing_line2" name="billing_line2" value="{{ old('billing_line2') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="billing_city" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">City *</label>
                                <input type="text" id="billing_city" name="billing_city" value="{{ old('billing_city') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                                @error('billing_city')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="billing_state" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">State/Province</label>
                                <input type="text" id="billing_state" name="billing_state" value="{{ old('billing_state') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="billing_postal_code" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Postal Code</label>
                                <input type="text" id="billing_postal_code" name="billing_postal_code" value="{{ old('billing_postal_code') }}" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                            </div>
                            <div>
                                <label for="billing_country" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Country *</label>
                                <input type="text" id="billing_country" name="billing_country" value="{{ old('billing_country', 'US') }}" maxlength="2" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" />
                                @error('billing_country')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="front-card p-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Payment Method</h2>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 rounded-md border border-sf-border p-4 cursor-pointer hover:border-sf-accent-secondary dark:border-[rgba(114,138,221,0.35)]">
                            <input type="radio" name="payment_method" value="card" @checked(old('payment_method') === 'card' || !old('payment_method')) required class="text-sf-accent-primary focus:ring-sf-accent-secondary" />
                            <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Credit/Debit Card</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-md border border-sf-border p-4 cursor-pointer hover:border-sf-accent-secondary dark:border-[rgba(114,138,221,0.35)]">
                            <input type="radio" name="payment_method" value="paypal" @checked(old('payment_method') === 'paypal') required class="text-sf-accent-primary focus:ring-sf-accent-secondary" />
                            <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">PayPal</span>
                        </label>
                        <label class="flex items-center gap-3 rounded-md border border-sf-border p-4 cursor-pointer hover:border-sf-accent-secondary dark:border-[rgba(114,138,221,0.35)]">
                            <input type="radio" name="payment_method" value="stripe" @checked(old('payment_method') === 'stripe') required class="text-sf-accent-primary focus:ring-sf-accent-secondary" />
                            <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Stripe</span>
                        </label>
                    </div>
                    @error('payment_method')<p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                    <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Note: This is a demo checkout. No actual payment will be processed.</p>
                </div>

                <div class="front-card p-6">
                    <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Order Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100">{{ old('notes') }}</textarea>
                </div>
            </div>

            <aside class="front-card p-6 h-fit">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Order Summary</h2>
                <div class="space-y-3 text-sm">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                            <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <span>${{ number_format($item->total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 space-y-3 border-t border-sf-border pt-4 text-sm dark:border-[rgba(114,138,221,0.25)]">
                    <div class="flex justify-between text-slate-600 dark:text-slate-300">
                        <span>Subtotal</span>
                        <span class="font-semibold text-slate-900 dark:text-slate-100">${{ number_format($cart->subtotal, 2) }}</span>
                    </div>
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
                <button type="submit" class="mt-6 w-full rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Place Order</button>
            </aside>
        </form>
    </div>
</section>

@push('scripts')
<script>
document.getElementById('billing_same_as_shipping').addEventListener('change', function() {
    const fields = document.getElementById('billing-fields');
    const inputs = fields.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.disabled = this.checked;
        if (this.checked) {
            input.removeAttribute('required');
        } else {
            const name = input.getAttribute('name');
            if (name && (name.includes('billing_name') || name.includes('billing_line1') || name.includes('billing_city') || name.includes('billing_country'))) {
                input.setAttribute('required', 'required');
            }
        }
    });
});
</script>
@endpush
@endsection
