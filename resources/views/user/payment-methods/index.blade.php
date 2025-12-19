@extends('layouts.user')

@section('content')
<div class="user-shell px-6 py-10">
    <div class="mx-auto max-w-4xl">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">Payment Methods</h1>
                <p class="text-sm text-slate-600 dark:text-slate-300">Manage your saved payment methods</p>
            </div>
            <button onclick="document.getElementById('new-payment-form').classList.toggle('hidden')" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add Payment Method</button>
        </div>

        <div id="new-payment-form" class="user-card p-6 mb-6 hidden">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Add Payment Method</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Note: This is a demo. In production, integrate with payment providers like Stripe or PayPal.</p>
            <form method="POST" action="{{ route('user.payment-methods.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="provider" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Provider *</label>
                    <select id="provider" name="provider" required class="w-full rounded-md text-sm">
                        <option value="card">Credit/Debit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="stripe">Stripe</option>
                    </select>
                </div>
                <div>
                    <label for="provider_reference" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Provider Reference *</label>
                    <input type="text" id="provider_reference" name="provider_reference" placeholder="e.g., card_1234..." required class="w-full rounded-md text-sm" />
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="brand" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Brand</label>
                        <input type="text" id="brand" name="brand" placeholder="e.g., Visa, Mastercard" class="w-full rounded-md text-sm" />
                    </div>
                    <div>
                        <label for="last4" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Last 4 Digits</label>
                        <input type="text" id="last4" name="last4" maxlength="4" placeholder="1234" class="w-full rounded-md text-sm" />
                    </div>
                </div>
                <div>
                    <label for="expires_on" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Expires On</label>
                    <input type="date" id="expires_on" name="expires_on" class="w-full rounded-md text-sm" />
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_default" value="1" class="rounded" />
                    <span>Set as default payment method</span>
                </label>
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add Payment Method</button>
            </form>
        </div>

        <div class="space-y-4">
            @forelse($paymentMethods as $method)
                <div class="user-card p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 capitalize">{{ $method->provider }}</h3>
                                @if($method->is_default)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-400">Default</span>
                                @endif
                            </div>
                            @if($method->brand)
                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">{{ $method->brand }}</p>
                            @endif
                            @if($method->last4)
                                <p class="text-sm text-slate-600 dark:text-slate-300">**** **** **** {{ $method->last4 }}</p>
                            @endif
                            @if($method->expires_on)
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Expires: {{ \Carbon\Carbon::parse($method->expires_on)->format('M Y') }}</p>
                            @endif
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Reference: {{ $method->provider_reference }}</p>
                        </div>
                        <div class="flex gap-2">
                            @if(!$method->is_default)
                                <form method="POST" action="{{ route('user.payment-methods.update', $method->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_default" value="1" />
                                    <button type="submit" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Set Default</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('user.payment-methods.destroy', $method->id) }}" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-500 transition hover:text-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="user-card p-6 text-center">
                    <p class="text-sm text-slate-500 dark:text-slate-300">No payment methods saved yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
