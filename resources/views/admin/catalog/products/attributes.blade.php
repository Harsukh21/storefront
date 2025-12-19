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
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Product Attributes</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Manage attributes for {{ $product->name }}</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.catalog.products.edit', $product->id) }}" class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">Edit Product</a>
        </div>
    </div>

    <!-- Add Attribute Form -->
    <div class="admin-card mt-6 p-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Add Attribute</h2>
        <form method="POST" action="{{ route('admin.catalog.products.attributes.store', $product->id) }}" class="space-y-6">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="attribute_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Attribute Name *</label>
                    <input id="attribute_name" name="attribute_name" type="text" value="{{ old('attribute_name') }}" required class="mt-2 w-full rounded-md" placeholder="e.g., Color, Size, Material" />
                    @error('attribute_name')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="attribute_value" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Attribute Value *</label>
                    <input id="attribute_value" name="attribute_value" type="text" value="{{ old('attribute_value') }}" required class="mt-2 w-full rounded-md" placeholder="e.g., Red, Large, Cotton" />
                    @error('attribute_value')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add Attribute</button>
            </div>
        </form>
    </div>

    <!-- Attributes List -->
    @if($attributes->count() > 0)
    <div class="admin-card mt-6 overflow-hidden">
        <div class="px-6 pt-6">
            <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Current Attributes ({{ $attributes->count() }})</h2>
        </div>
        <table class="min-w-full divide-y divide-sf-border/60 text-sm dark:divide-[rgba(114,138,221,0.25)]">
            <thead class="bg-sf-surface/70 dark:bg-sf-night-800/80">
                <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                    <th class="px-4 py-3">Attribute Name</th>
                    <th class="px-4 py-3">Value</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sf-border/60 dark:divide-[rgba(114,138,221,0.25)]">
                @foreach($attributes as $attribute)
                    <tr class="bg-white/60 text-slate-700 transition hover:bg-sf-accent-primary/5 dark:bg-sf-night-900/60 dark:text-slate-200">
                        <td class="px-4 py-3 font-semibold text-slate-900 dark:text-slate-100">{{ $attribute->attribute_name }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $attribute->attribute_value }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex gap-2">
                                <a href="#" onclick="editAttribute({{ $attribute->id }}, '{{ addslashes($attribute->attribute_name) }}', '{{ addslashes($attribute->attribute_value) }}')" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Edit</a>
                                <form method="POST" action="{{ route('admin.catalog.products.attributes.destroy', [$product->id, $attribute->id]) }}" onsubmit="return confirm('Delete this attribute?')" class="inline">
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-slate-100">No attributes yet</h3>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Add your first attribute using the form above.</p>
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
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Attribute</h3>
            <form id="edit-form" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_attribute_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Attribute Name *</label>
                    <input type="text" id="edit_attribute_name" name="attribute_name" required class="mt-2 w-full rounded-md" />
                </div>
                <div>
                    <label for="edit_attribute_value" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Attribute Value *</label>
                    <input type="text" id="edit_attribute_value" name="attribute_value" required class="mt-2 w-full rounded-md" />
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Update</button>
                    <button type="button" onclick="closeEditModal()" class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editAttribute(id, name, value) {
    document.getElementById('edit_attribute_name').value = name;
    document.getElementById('edit_attribute_value').value = value;
    document.getElementById('edit-form').action = '{{ route("admin.catalog.products.attributes.update", [$product->id, ":attribute"]) }}'.replace(':attribute', id);
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
