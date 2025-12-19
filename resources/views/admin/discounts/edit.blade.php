@extends('layouts.admin-panel')

@section('title', 'Edit Discount - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.discounts.index') }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Edit Discount</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Update discount code: {{ $discount->code }}</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Redemptions: {{ $redemptionCount }}</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="admin-card p-8">
        <form method="POST" action="{{ route('admin.discounts.update', $discount->id) }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div>
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Basic Information</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="code" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Discount Code *</label>
                        <input 
                            id="code" 
                            name="code" 
                            type="text" 
                            value="{{ old('code', $discount->code) }}" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        @error('code')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Type *</label>
                        <select 
                            id="type" 
                            name="type" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                        >
                            <option value="percentage" {{ old('type', $discount->type) === 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ old('type', $discount->type) === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        </select>
                    </div>
                    <div>
                        <label for="value" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Value *</label>
                        <input 
                            id="value" 
                            name="value" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('value', $discount->value) }}" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Percentage (0-100) or fixed amount</p>
                        @error('value')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="minimum_subtotal" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Minimum Subtotal</label>
                        <input 
                            id="minimum_subtotal" 
                            name="minimum_subtotal" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('minimum_subtotal', $discount->minimum_subtotal) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Usage Limits</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="usage_limit" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Total Usage Limit</label>
                        <input 
                            id="usage_limit" 
                            name="usage_limit" 
                            type="number" 
                            value="{{ old('usage_limit', $discount->usage_limit) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Leave blank for unlimited uses</p>
                    </div>
                    <div>
                        <label for="usage_limit_per_user" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Usage Limit Per User</label>
                        <input 
                            id="usage_limit_per_user" 
                            name="usage_limit_per_user" 
                            type="number" 
                            value="{{ old('usage_limit_per_user', $discount->usage_limit_per_user) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Leave blank for unlimited uses per user</p>
                    </div>
                </div>
            </div>

            <!-- Validity Period -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Validity Period</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="starts_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Starts At</label>
                        <input 
                            id="starts_at" 
                            name="starts_at" 
                            type="datetime-local" 
                            value="{{ old('starts_at', $discount->starts_at ? \Carbon\Carbon::parse($discount->starts_at)->format('Y-m-d\TH:i') : '') }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                    <div>
                        <label for="expires_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Expires At</label>
                        <input 
                            id="expires_at" 
                            name="expires_at" 
                            type="datetime-local" 
                            value="{{ old('expires_at', $discount->expires_at ? \Carbon\Carbon::parse($discount->expires_at)->format('Y-m-d\TH:i') : '') }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Settings</h2>
                <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                    <input 
                        type="checkbox" 
                        id="is_active" 
                        name="is_active" 
                        value="1" 
                        {{ old('is_active', $discount->is_active) ? 'checked' : '' }} 
                        class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)]" 
                    />
                    <label for="is_active" class="text-sm font-medium text-slate-700 dark:text-slate-200">Active</label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <button 
                    type="submit" 
                    class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Update Discount
                </button>
                <a 
                    href="{{ route('admin.discounts.index') }}" 
                    class="rounded-lg border border-sf-border/60 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
