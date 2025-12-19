@extends('layouts.admin-panel')

@section('title', 'Edit Product - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.catalog.products.index') }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Edit Product</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Update product information for {{ $product->name }}</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="admin-card p-8">
        <form method="POST" action="{{ route('admin.catalog.products.update', $product->id) }}" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div>
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Basic Information</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Product Name *</label>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            value="{{ old('name', $product->name) }}" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        @error('name')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Slug</label>
                        <input 
                            id="slug" 
                            name="slug" 
                            type="text" 
                            value="{{ old('slug', $product->slug) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        @error('slug')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Category</label>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                        >
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="brand_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Brand</label>
                        <select 
                            id="brand_id" 
                            name="brand_id" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                        >
                            <option value="">— None —</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id) == $brand->id)>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Pricing</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="price" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Price *</label>
                        <input 
                            id="price" 
                            name="price" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('price', $product->price) }}" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                        @error('price')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="compare_at_price" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Compare at Price</label>
                        <input 
                            id="compare_at_price" 
                            name="compare_at_price" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('compare_at_price', $product->compare_at_price) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Inventory</h2>
                <div>
                    <label for="sku" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">SKU</label>
                    <input 
                        id="sku" 
                        name="sku" 
                        type="text" 
                        value="{{ old('sku', $product->sku) }}" 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                    />
                </div>
            </div>

            <!-- Description -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Description</h2>
                <div class="space-y-6">
                    <div>
                        <label for="short_description" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Short Description</label>
                        <textarea 
                            id="short_description" 
                            name="short_description" 
                            rows="3" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                        >{{ old('short_description', $product->short_description) }}</textarea>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Full Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="6" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Dimensions -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Dimensions</h2>
                <div class="grid gap-6 md:grid-cols-4">
                    <div>
                        <label for="weight" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Weight (lbs)</label>
                        <input 
                            id="weight" 
                            name="weight" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('weight', $product->weight) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                    <div>
                        <label for="width" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Width (in)</label>
                        <input 
                            id="width" 
                            name="width" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('width', $product->width) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                    <div>
                        <label for="height" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Height (in)</label>
                        <input 
                            id="height" 
                            name="height" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('height', $product->height) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                    <div>
                        <label for="depth" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Depth (in)</label>
                        <input 
                            id="depth" 
                            name="depth" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('depth', $product->depth) }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        />
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Settings</h2>
                <div class="flex flex-wrap gap-6 rounded-lg bg-slate-50 p-6 dark:bg-sf-night-900/50">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1" 
                            @checked(old('is_active', $product->is_active)) 
                            class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)]" 
                        />
                        Visible in storefront
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        <input 
                            type="checkbox" 
                            name="is_featured" 
                            value="1" 
                            @checked(old('is_featured', $product->is_featured)) 
                            class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)]" 
                        />
                        Featured product
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3 border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <button 
                    type="submit" 
                    class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Update Product
                </button>
                <a 
                    href="{{ route('admin.catalog.products.index') }}" 
                    class="rounded-lg border border-sf-border/60 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Product Management Links -->
    <div class="admin-card mt-6 p-8">
        <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Product Management</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <a 
                href="{{ route('admin.catalog.products.images', $product->id) }}" 
                class="flex items-center gap-4 rounded-lg border border-sf-border/60 bg-white p-4 transition-all hover:border-sf-accent-primary hover:shadow-md dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-800"
            >
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                    <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 dark:text-slate-100">Images</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Manage product images</p>
                </div>
            </a>
            <a 
                href="{{ route('admin.catalog.products.variants', $product->id) }}" 
                class="flex items-center gap-4 rounded-lg border border-sf-border/60 bg-white p-4 transition-all hover:border-sf-accent-primary hover:shadow-md dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-800"
            >
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                    <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 dark:text-slate-100">Variants</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Manage product variants</p>
                </div>
            </a>
            <a 
                href="{{ route('admin.catalog.products.attributes', $product->id) }}" 
                class="flex items-center gap-4 rounded-lg border border-sf-border/60 bg-white p-4 transition-all hover:border-sf-accent-primary hover:shadow-md dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-800"
            >
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                    <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 dark:text-slate-100">Attributes</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Manage product attributes</p>
                </div>
            </a>
            <a 
                href="{{ route('admin.catalog.products.options', $product->id) }}" 
                class="flex items-center gap-4 rounded-lg border border-sf-border/60 bg-white p-4 transition-all hover:border-sf-accent-primary hover:shadow-md dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-800"
            >
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                    <svg class="h-6 w-6 text-sf-accent-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 dark:text-slate-100">Options</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Manage option types</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
