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
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Product Options</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Manage option types and values for {{ $product->name }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.catalog.products.edit', $product->id) }}" class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">Edit Product</a>
        </div>
    </div>

    <!-- Add Option Type Form -->
    <div class="admin-card mt-6 p-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Add Option Type</h2>
        <form method="POST" action="{{ route('admin.catalog.products.options.types.store', $product->id) }}" class="space-y-6">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Option Name *</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required class="mt-2 w-full rounded-md" placeholder="e.g., size, color" />
                    @error('name')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="display_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Display Name</label>
                    <input id="display_name" name="display_name" type="text" value="{{ old('display_name') }}" class="mt-2 w-full rounded-md" placeholder="e.g., Size, Color" />
                    @error('display_name')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add Option Type</button>
            </div>
        </form>
    </div>

    <!-- Option Types List -->
    @if($optionTypes->count() > 0)
        @foreach($optionTypes as $optionType)
            <div class="admin-card mt-6 overflow-hidden">
                <div class="px-6 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $optionType->display_name ?? $optionType->name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Name: {{ $optionType->name }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="#" onclick="editOptionType({{ $optionType->id }}, '{{ addslashes($optionType->name) }}', '{{ addslashes($optionType->display_name ?? '') }}')" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.catalog.products.options.types.destroy', [$product->id, $optionType->id]) }}" onsubmit="return confirm('Delete this option type and all its values?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-semibold text-red-400 transition hover:text-red-300">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-6">
                    <!-- Add Value Form -->
                    <form method="POST" action="{{ route('admin.catalog.products.options.values.store', [$product->id, $optionType->id]) }}" class="mb-4 flex flex-col gap-2 md:flex-row">
                        @csrf
                        <div class="flex-1">
                            <label for="value-{{ $optionType->id }}" class="sr-only">Value</label>
                            <input type="text" id="value-{{ $optionType->id }}" name="value" placeholder="Add value (e.g., Small, Red)" required class="w-full rounded-md" />
                            @error('value')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="w-24">
                            <label for="sort_order-{{ $optionType->id }}" class="sr-only">Sort Order</label>
                            <input type="number" id="sort_order-{{ $optionType->id }}" name="sort_order" placeholder="Order" value="0" class="w-full rounded-md" />
                            @error('sort_order')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add</button>
                    </form>

                    <!-- Values List -->
                    @if($optionType->values->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($optionType->values as $value)
                                <div class="flex items-center gap-2 rounded-lg border border-sf-border/60 bg-white px-3 py-1.5 dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-800">
                                    <span class="text-sm text-slate-900 dark:text-slate-100">{{ $value->value }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">({{ $value->sort_order }})</span>
                                    <a href="#" onclick="editValue({{ $optionType->id }}, {{ $value->id }}, '{{ addslashes($value->value) }}', {{ $value->sort_order }})" class="text-xs text-sf-accent-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.catalog.products.options.values.destroy', [$product->id, $optionType->id, $value->id]) }}" onsubmit="return confirm('Delete this value?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400">×</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-300">No values yet. Add values above.</p>
                    @endif
                </div>
            </div>
        @endforeach
    @else
    <div class="admin-card mt-6 p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-slate-100">No option types yet</h3>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Add your first option type using the form above.</p>
    </div>
    @endif

    <!-- Edit Option Type Modal -->
    <div id="edit-type-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="admin-card relative max-w-md w-full p-6">
            <button onclick="closeTypeModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Option Type</h3>
            <form id="edit-type-form" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_type_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Option Name *</label>
                    <input type="text" id="edit_type_name" name="name" required class="mt-2 w-full rounded-md" />
                </div>
                <div>
                    <label for="edit_type_display_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Display Name</label>
                    <input type="text" id="edit_type_display_name" name="display_name" class="mt-2 w-full rounded-md" />
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Update</button>
                    <button type="button" onclick="closeTypeModal()" class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Value Modal -->
    <div id="edit-value-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="admin-card relative max-w-md w-full p-6">
            <button onclick="closeValueModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Value</h3>
            <form id="edit-value-form" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_value_value" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Value *</label>
                    <input type="text" id="edit_value_value" name="value" required class="mt-2 w-full rounded-md" />
                </div>
                <div>
                    <label for="edit_value_sort_order" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Sort Order</label>
                    <input type="number" id="edit_value_sort_order" name="sort_order" class="mt-2 w-full rounded-md" />
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Update</button>
                    <button type="button" onclick="closeValueModal()" class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
function editOptionType(id, name, displayName) {
    document.getElementById('edit_type_name').value = name;
    document.getElementById('edit_type_display_name').value = displayName || '';
    document.getElementById('edit-type-form').action = '{{ route("admin.catalog.products.options.types.update", [$product->id, ":type"]) }}'.replace(':type', id);
    document.getElementById('edit-type-modal').classList.remove('hidden');
    document.getElementById('edit-type-modal').classList.add('flex');
}

function closeTypeModal() {
    document.getElementById('edit-type-modal').classList.add('hidden');
    document.getElementById('edit-type-modal').classList.remove('flex');
}

function editValue(typeId, valueId, value, sortOrder) {
    document.getElementById('edit_value_value').value = value;
    document.getElementById('edit_value_sort_order').value = sortOrder;
    document.getElementById('edit-value-form').action = '{{ route("admin.catalog.products.options.values.update", [$product->id, ":type", ":value"]) }}'.replace(':type', typeId).replace(':value', valueId);
    document.getElementById('edit-value-modal').classList.remove('hidden');
    document.getElementById('edit-value-modal').classList.add('flex');
}

function closeValueModal() {
    document.getElementById('edit-value-modal').classList.add('hidden');
    document.getElementById('edit-value-modal').classList.remove('flex');
}

// Close modals if clicking outside
document.getElementById('edit-type-modal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeTypeModal();
    }
});

document.getElementById('edit-value-modal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeValueModal();
    }
});
</script>
@endsection
