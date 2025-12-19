@extends('layouts.admin-panel')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.catalog.products.edit', $product->id) }}" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Product Variants</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Manage variants for {{ $product->name }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.catalog.products.edit', $product->id) }}" class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">Edit Product</a>
        </div>
    </div>

    <!-- Add Variant Form -->
    <div class="admin-card mt-6 p-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Add Variant</h2>
        <form method="POST" action="{{ route('admin.catalog.products.variants.store', $product->id) }}" class="space-y-6">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Variant Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" class="mt-2 w-full rounded-md" placeholder="e.g., Small, Red" />
                    @error('name')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="sku" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">SKU *</label>
                    <input id="sku" name="sku" type="text" value="{{ old('sku') }}" required class="mt-2 w-full rounded-md" />
                    @error('sku')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="barcode" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Barcode</label>
                    <input id="barcode" name="barcode" type="text" value="{{ old('barcode') }}" class="mt-2 w-full rounded-md" />
                    @error('barcode')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="price" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Price</label>
                    <input id="price" name="price" type="number" step="0.01" value="{{ old('price') }}" class="mt-2 w-full rounded-md" />
                    @error('price')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="compare_at_price" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Compare At Price</label>
                    <input id="compare_at_price" name="compare_at_price" type="number" step="0.01" value="{{ old('compare_at_price') }}" class="mt-2 w-full rounded-md" />
                    @error('compare_at_price')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center gap-2 pt-8">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-sf-border text-sf-accent-primary" />
                    <label for="is_active" class="text-sm font-medium text-slate-700 dark:text-slate-200">Active</label>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add Variant</button>
            </div>
        </form>
    </div>

    <!-- Variants List -->
    @if($variants->count() > 0)
    <div class="admin-card mt-6 overflow-hidden">
        <div class="px-6 pt-6">
            <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Current Variants ({{ $variants->count() }})</h2>
        </div>
        <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
            <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">SKU</th>
                    <th class="px-4 py-3">Barcode</th>
                    <th class="px-4 py-3">Price</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                @foreach($variants as $variant)
                    <tr class="bg-white/60 text-slate-700 transition hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-slate-100">{{ $variant->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $variant->sku }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $variant->barcode ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-slate-900 dark:text-slate-100">
                            ${{ number_format($variant->price ?? $product->price, 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $variant->is_active ? 'bg-emerald-500/10 text-emerald-400' : 'bg-slate-500/10 text-slate-300' }}">
                                {{ $variant->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-2">
                                <a href="#" onclick="editVariant({{ $variant->id }}, '{{ addslashes($variant->name ?? '') }}', '{{ addslashes($variant->sku) }}', '{{ addslashes($variant->barcode ?? '') }}', '{{ $variant->price ?? '' }}', '{{ $variant->compare_at_price ?? '' }}', {{ $variant->is_active ? 'true' : 'false' }})" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Edit</a>
                                <form method="POST" action="{{ route('admin.catalog.products.variants.destroy', [$product->id, $variant->id]) }}" onsubmit="return confirm('Delete this variant?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-red-400 transition hover:text-red-300">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="admin-card mt-6 p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
        </svg>
        <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-slate-100">No variants yet</h3>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Add your first variant using the form above.</p>
    </div>
    @endif

    <!-- Edit Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="admin-card relative max-w-md w-full p-6">
            <button onclick="closeEditModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Variant</h3>
            <form id="edit-form" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Variant Name</label>
                    <input type="text" id="edit_name" name="name" class="mt-2 w-full rounded-md" />
                </div>
                <div>
                    <label for="edit_sku" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">SKU *</label>
                    <input type="text" id="edit_sku" name="sku" required class="mt-2 w-full rounded-md" />
                </div>
                <div>
                    <label for="edit_barcode" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Barcode</label>
                    <input type="text" id="edit_barcode" name="barcode" class="mt-2 w-full rounded-md" />
                </div>
                <div>
                    <label for="edit_price" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Price</label>
                    <input type="number" step="0.01" id="edit_price" name="price" class="mt-2 w-full rounded-md" />
                </div>
                <div>
                    <label for="edit_compare_at_price" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Compare At Price</label>
                    <input type="number" step="0.01" id="edit_compare_at_price" name="compare_at_price" class="mt-2 w-full rounded-md" />
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="rounded border-sf-border text-sf-accent-primary" />
                    <label for="edit_is_active" class="text-sm font-medium text-slate-700 dark:text-slate-200">Active</label>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Update</button>
                    <button type="button" onclick="closeEditModal()" class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
function editVariant(id, name, sku, barcode, price, compareAtPrice, isActive) {
    document.getElementById('edit_name').value = name || '';
    document.getElementById('edit_sku').value = sku;
    document.getElementById('edit_barcode').value = barcode || '';
    document.getElementById('edit_price').value = price || '';
    document.getElementById('edit_compare_at_price').value = compareAtPrice || '';
    document.getElementById('edit_is_active').checked = isActive;
    document.getElementById('edit-form').action = '{{ route("admin.catalog.products.variants.update", [$product->id, ":variant"]) }}'.replace(':variant', id);
    document.getElementById('edit-modal').classList.remove('hidden');
    document.getElementById('edit-modal').classList.add('flex');
}

function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
    document.getElementById('edit-modal').classList.remove('flex');
}

// Close modal if clicking outside
document.getElementById('edit-modal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeEditModal();
    }
});
</script>
@endsection
