@extends('layouts.admin-panel')

@section('title', 'Product Images - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.catalog.products.edit', $product->id) }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Product Images</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Manage images for {{ $product->name }}</p>
        </div>
    </div>

    <!-- Add Image Form -->
    <div class="admin-card mt-6 p-6">
        <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Add New Image</h2>
        <form method="POST" action="{{ route('admin.catalog.products.images.store', $product->id) }}" class="space-y-6">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="file_path" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Image URL *</label>
                    <input type="url" id="file_path" name="file_path" value="{{ old('file_path') }}" placeholder="https://example.com/image.jpg" required class="mt-2 w-full rounded-md" />
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Enter the full URL of the image</p>
                    @error('file_path')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="alt_text" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Alt Text</label>
                    <input type="text" id="alt_text" name="alt_text" value="{{ old('alt_text') }}" placeholder="Product image description" class="mt-2 w-full rounded-md" />
                    @error('alt_text')
                        <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" id="is_primary" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }} class="rounded border-sf-border text-sf-accent-primary" />
                <label for="is_primary" class="text-sm font-medium text-slate-700 dark:text-slate-200">Set as primary image</label>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add Image</button>
            </div>
        </form>
    </div>

    <!-- Images Grid -->
    @if($images->count() > 0)
    <div class="admin-card mt-6 overflow-hidden">
        <div class="px-6 pt-6">
            <h2 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Product Images ({{ $images->count() }})</h2>
        </div>
        <div class="grid gap-4 p-6 md:grid-cols-2 lg:grid-cols-3" id="images-grid">
            @foreach($images as $image)
                <div class="group relative overflow-hidden rounded-lg border border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]" data-image-id="{{ $image->id }}">
                    <div class="relative aspect-square overflow-hidden bg-slate-100 dark:bg-sf-night-800">
                        <img src="{{ $image->file_path }}" alt="{{ $image->alt_text ?? $product->name }}" class="h-full w-full object-cover" />
                        @if($image->is_primary)
                            <div class="absolute top-2 left-2 rounded-full bg-sf-accent-primary px-2 py-1 text-xs font-semibold text-sf-night-900">Primary</div>
                        @endif
                        <div class="absolute inset-0 flex items-center justify-center gap-2 bg-black/60 opacity-0 transition-opacity group-hover:opacity-100">
                            @if(!$image->is_primary)
                                <form method="POST" action="{{ route('admin.catalog.products.images.set-primary', [$product->id, $image->id]) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="rounded-lg bg-sf-accent-primary px-3 py-1.5 text-xs font-semibold text-sf-night-900 transition hover:opacity-90">Set Primary</button>
                                </form>
                            @endif
                            <a href="#" onclick="editImage({{ $image->id }}, '{{ addslashes($image->file_path) }}', '{{ addslashes($image->alt_text ?? '') }}', {{ $image->is_primary ? 'true' : 'false' }})" class="rounded-lg bg-white px-3 py-1.5 text-xs font-semibold text-slate-900 transition hover:opacity-90">Edit</a>
                            <form method="POST" action="{{ route('admin.catalog.products.images.destroy', [$product->id, $image->id]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="p-3 bg-white dark:bg-sf-night-800">
                        <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2">{{ $image->alt_text ?: 'No alt text' }}</p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-500">Order: {{ $image->sort_order }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="admin-card mt-6 p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-slate-100">No images yet</h3>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Add your first product image using the form above.</p>
    </div>
    @endif

    <!-- Edit Image Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="admin-card relative max-w-md w-full p-6">
            <button onclick="closeEditModal()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Image</h3>
            <form id="edit-form" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_file_path" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Image URL *</label>
                    <input type="url" id="edit_file_path" name="file_path" required class="mt-2 w-full rounded-md" />
                </div>
                <div>
                    <label for="edit_alt_text" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Alt Text</label>
                    <input type="text" id="edit_alt_text" name="alt_text" class="mt-2 w-full rounded-md" />
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="edit_is_primary" name="is_primary" value="1" class="rounded border-sf-border text-sf-accent-primary" />
                    <label for="edit_is_primary" class="text-sm font-medium text-slate-700 dark:text-slate-200">Set as primary image</label>
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imagesGrid = document.getElementById('images-grid');
    const editModal = document.getElementById('edit-modal');
    const editForm = document.getElementById('edit-form');

    // Initialize SortableJS
    if (imagesGrid) {
        new Sortable(imagesGrid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function (evt) {
                const order = Array.from(imagesGrid.children).map(item => item.dataset.imageId);
                fetch('{{ route("admin.catalog.products.images.reorder", $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (window.Toast) {
                            window.Toast.show({ type: 'success', message: 'Image order updated.' });
                        }
                        setTimeout(() => window.location.reload(), 500);
                    } else {
                        if (window.Toast) {
                            window.Toast.show({ type: 'danger', message: 'Failed to update image order.' });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error reordering images:', error);
                    if (window.Toast) {
                        window.Toast.show({ type: 'danger', message: 'Error reordering images.' });
                    }
                });
            },
        });
    }

    window.editImage = function(imageId, filePath, altText, isPrimary) {
        document.getElementById('edit_file_path').value = filePath;
        document.getElementById('edit_alt_text').value = altText || '';
        document.getElementById('edit_is_primary').checked = isPrimary;
        editForm.action = '{{ route("admin.catalog.products.images.update", [$product->id, ":image"]) }}'.replace(':image', imageId);
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
    }

    window.closeEditModal = function() {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    }

    // Close modal if clicking outside
    editModal.addEventListener('click', function(event) {
        if (event.target === editModal) {
            closeEditModal();
        }
    });
});
</script>
@endsection
