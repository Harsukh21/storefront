@extends('layouts.admin-panel')

@section('title', 'Inventory Adjustments - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Inventory Adjustments</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">View and record inventory adjustments</p>
        </div>
        <a 
            href="{{ route('admin.inventory.index') }}" 
            class="inline-flex items-center gap-2 rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
        >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Inventory
        </a>
    </div>

    <!-- Add Adjustment Form -->
    <div class="admin-card mb-6 p-8">
        <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Record Adjustment</h2>
        <form method="POST" action="{{ route('admin.inventory.adjustments.store') }}" class="space-y-6">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="inventory_item_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Inventory Item ID *</label>
                    <input 
                        id="inventory_item_id" 
                        name="inventory_item_id" 
                        type="number" 
                        required 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        placeholder="123"
                    />
                </div>
                <div>
                    <label for="adjustment_type" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Type *</label>
                    <select 
                        id="adjustment_type" 
                        name="adjustment_type" 
                        required 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                    >
                        <option value="add">Add Stock</option>
                        <option value="remove">Remove Stock</option>
                        <option value="set">Set Quantity</option>
                        <option value="damaged">Damaged</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
                <div>
                    <label for="quantity_change" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Quantity Change *</label>
                    <input 
                        id="quantity_change" 
                        name="quantity_change" 
                        type="number" 
                        required 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        placeholder="+10 or -5"
                    />
                </div>
                <div>
                    <label for="note" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Note</label>
                    <textarea 
                        id="note" 
                        name="note" 
                        rows="3" 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                        placeholder="Reason for adjustment..."
                    ></textarea>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button 
                    type="submit" 
                    class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Record Adjustment
                </button>
            </div>
        </form>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-6 p-6">
        <form method="GET" class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="product_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Product ID</label>
                <input 
                    name="product_id" 
                    type="number" 
                    value="{{ $filters['product_id'] }}" 
                    placeholder="Filter by Product ID" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                />
            </div>
            <div>
                <label for="adjustment_type" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Type</label>
                <select 
                    name="adjustment_type" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                >
                    <option value="">All Types</option>
                    <option value="add" @selected($filters['adjustment_type'] === 'add')>Add</option>
                    <option value="remove" @selected($filters['adjustment_type'] === 'remove')>Remove</option>
                    <option value="set" @selected($filters['adjustment_type'] === 'set')>Set</option>
                    <option value="damaged" @selected($filters['adjustment_type'] === 'damaged')>Damaged</option>
                    <option value="returned" @selected($filters['adjustment_type'] === 'returned')>Returned</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button 
                    type="submit" 
                    class="flex-1 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Filter
                </button>
                <a 
                    href="{{ route('admin.inventory.adjustments.index') }}" 
                    class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Adjustments History -->
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
                <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Change</th>
                        <th class="px-6 py-4">Admin</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Note</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                    @forelse ($adjustments as $adjustment)
                        <tr class="bg-white/60 text-slate-700 transition-colors hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                            <td class="px-6 py-4">
                                @if($adjustment->product_name)
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $adjustment->product_name }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $adjustment->product_sku ?? $adjustment->variant_sku }}</p>
                                @else
                                    <span class="text-slate-500 dark:text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold 
                                    {{ $adjustment->adjustment_type === 'add' ? 'bg-emerald-500/10 text-emerald-500' : '' }}
                                    {{ $adjustment->adjustment_type === 'remove' ? 'bg-red-500/10 text-red-500' : '' }}
                                    {{ $adjustment->adjustment_type === 'set' ? 'bg-blue-500/10 text-blue-500' : '' }}
                                    {{ in_array($adjustment->adjustment_type, ['damaged', 'returned']) ? 'bg-amber-500/10 text-amber-500' : '' }}">
                                    {{ ucfirst($adjustment->adjustment_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ $adjustment->quantity_change > 0 ? '+' : '' }}{{ $adjustment->quantity_change }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $adjustment->admin_name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($adjustment->created_at)->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $adjustment->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No adjustments found</h3>
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Try adjusting your filter criteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($adjustments->hasPages())
            <div class="border-t border-sf-border/60 px-6 py-4 dark:border-[rgba(114,138,221,0.25)]">
                {{ $adjustments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
