@extends('layouts.admin-panel')

@section('title', 'Edit Tax Rate - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.tax-rates.index') }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Edit Tax Rate</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Update tax rate: {{ $taxRate->name }}</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="admin-card p-8">
        <form method="POST" action="{{ route('admin.tax-rates.update', $taxRate->id) }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div>
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Basic Information</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Name *</label>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            value="{{ old('name', $taxRate->name) }}" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        @error('name')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="rate" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Rate *</label>
                        <input 
                            id="rate" 
                            name="rate" 
                            type="number" 
                            step="0.0001" 
                            min="0" 
                            max="1" 
                            value="{{ old('rate', $taxRate->rate) }}" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Enter as decimal (0.0825 = 8.25%)</p>
                        @error('rate')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Location</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="country" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Country Code</label>
                        <input 
                            id="country" 
                            name="country" 
                            type="text" 
                            maxlength="2" 
                            value="{{ old('country', $taxRate->country) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">State</label>
                        <input 
                            id="state" 
                            name="state" 
                            type="text" 
                            value="{{ old('state', $taxRate->state) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">City</label>
                        <input 
                            id="city" 
                            name="city" 
                            type="text" 
                            value="{{ old('city', $taxRate->city) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Postal Code</label>
                        <input 
                            id="postal_code" 
                            name="postal_code" 
                            type="text" 
                            value="{{ old('postal_code', $taxRate->postal_code) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Advanced Settings</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="priority" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Priority</label>
                        <input 
                            id="priority" 
                            name="priority" 
                            type="number" 
                            min="0" 
                            value="{{ old('priority', $taxRate->priority) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Higher priority rates apply first</p>
                    </div>
                    <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                        <input 
                            type="checkbox" 
                            id="compound" 
                            name="compound" 
                            value="1" 
                            {{ old('compound', $taxRate->compound) ? 'checked' : '' }} 
                            class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)]" 
                        />
                        <label for="compound" class="text-sm font-medium text-slate-700 dark:text-slate-200">Compound Tax</label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <button 
                    type="submit" 
                    class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Update Tax Rate
                </button>
                <a 
                    href="{{ route('admin.tax-rates.index') }}" 
                    class="rounded-lg border border-sf-border/60 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
