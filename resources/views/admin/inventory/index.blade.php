@extends('layouts.admin-panel')

@section('title', 'Inventory - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Inventory Management</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Monitor and adjust inventory levels</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[2fr,1fr]">
        <!-- Inventory Table -->
        <section class="admin-card overflow-hidden">
            <div class="border-b border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Inventory Levels</h2>
                    <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Total items: {{ $inventory->total() }}</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
                    <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            <th class="px-6 py-4">Product</th>
                            <th class="px-6 py-4">On Hand</th>
                            <th class="px-6 py-4">Reserved</th>
                            <th class="px-6 py-4">Available</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                        @forelse ($inventory as $item)
                            <tr class="bg-white/60 text-slate-700 transition-colors hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->product_name ?? 'Unknown product' }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">SKU: {{ $item->sku ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-900 dark:text-slate-100">{{ $item->quantity_on_hand }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $item->quantity_reserved }}</td>
                                <td class="px-6 py-4 font-semibold {{ $item->quantity_available < 5 ? 'text-red-500' : 'text-slate-900 dark:text-slate-100' }}">
                                    {{ $item->quantity_available }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="mx-auto max-w-sm">
                                        <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No inventory records yet</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($inventory->hasPages())
                <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                    {{ $inventory->links() }}
                </div>
            @endif
        </section>

        <!-- Adjust Inventory Form -->
        <section class="admin-card p-6">
            <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Adjust Inventory</h2>
            <p class="mb-6 text-sm text-slate-600 dark:text-slate-400">Positive numbers add stock, negative numbers remove stock.</p>

            <form method="POST" action="{{ route('admin.inventory.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="product_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Product *</label>
                    <select 
                        id="product_id" 
                        name="product_id" 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                    >
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="quantity_change" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Quantity Change *</label>
                    <input 
                        id="quantity_change" 
                        name="quantity_change" 
                        type="number" 
                        value="{{ old('quantity_change') }}" 
                        required 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        placeholder="+10 or -5"
                    />
                    @error('quantity_change')
                        <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="note" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Note (optional)</label>
                    <textarea 
                        id="note" 
                        name="note" 
                        rows="3" 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                        placeholder="Reason for adjustment..."
                    >{{ old('note') }}</textarea>
                </div>
                <button 
                    type="submit" 
                    class="w-full rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Apply Adjustment
                </button>
            </form>
            <div class="mt-6">
                <a 
                    href="{{ route('admin.inventory.adjustments.index') }}" 
                    class="inline-flex items-center gap-2 text-sm font-semibold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                >
                    View Adjustment History
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </section>
    </div>
</div>
@endsection
