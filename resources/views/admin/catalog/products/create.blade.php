@extends('layouts.admin-panel')

@section('title', 'Create Product - ' . config('app.name') . ' Admin')

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
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Create Product</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Add product details, pricing, and availability</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="admin-card p-8">
        <form method="POST" action="{{ route('admin.catalog.products.store') }}" class="space-y-8">
            @csrf
            
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
                            value="{{ old('name') }}" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            placeholder="Product Name"
                        />
                        @error('name')
                            <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                            Slug
                            <span class="text-xs font-normal text-slate-500 dark:text-slate-400">(auto-generated if blank)</span>
                        </label>
                        <input 
                            id="slug" 
                            name="slug" 
                            type="text" 
                            value="{{ old('slug') }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            placeholder="product-slug"
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
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
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
                                <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>{{ $brand->name }}</option>
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
                            value="{{ old('price') }}" 
                            required 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            placeholder="0.00"
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
                            value="{{ old('compare_at_price') }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            placeholder="0.00"
                        />
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="border-t border-sf-border/60 pt-8 dark:border-[rgba(114,138,221,0.25)]">
                <h2 class="mb-6 text-lg font-bold text-slate-900 dark:text-slate-100">Inventory</h2>
                <div>
                    <label for="sku" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">
                        SKU
                        <span class="text-xs font-normal text-slate-500 dark:text-slate-400">(auto-generated if blank)</span>
                    </label>
                    <input 
                        id="sku" 
                        name="sku" 
                        type="text" 
                        value="{{ old('sku') }}" 
                        class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                        placeholder="SKU-001"
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
                            placeholder="Brief product description..."
                        >{{ old('short_description') }}</textarea>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Full Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="6" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                            placeholder="Detailed product description..."
                        >{{ old('description') }}</textarea>
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
                            value="{{ old('weight') }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            placeholder="0.00"
                        />
                    </div>
                    <div>
                        <label for="width" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Width (in)</label>
                        <input 
                            id="width" 
                            name="width" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('width') }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            placeholder="0.00"
                        />
                    </div>
                    <div>
                        <label for="height" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Height (in)</label>
                        <input 
                            id="height" 
                            name="height" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('height') }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            placeholder="0.00"
                        />
                    </div>
                    <div>
                        <label for="depth" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Depth (in)</label>
                        <input 
                            id="depth" 
                            name="depth" 
                            type="number" 
                            step="0.01" 
                            value="{{ old('depth') }}" 
                            class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            placeholder="0.00"
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
                            @checked(old('is_active', true)) 
                            class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)]" 
                        />
                        Visible in storefront
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                        <input 
                            type="checkbox" 
                            name="is_featured" 
                            value="1" 
                            @checked(old('is_featured')) 
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
                    Save Product
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
</div>
@endsection
