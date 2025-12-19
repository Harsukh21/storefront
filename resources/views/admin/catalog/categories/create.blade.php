@extends('layouts.admin-panel')

@section('title', 'Create Category - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8 flex items-center gap-3">
        <a 
            href="{{ route('admin.catalog.categories.index') }}" 
            class="flex h-10 w-10 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-sf-night-900 dark:hover:text-slate-200"
        >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Create Category</h1>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Define category metadata and optional parent</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="admin-card p-8">
        <form method="POST" action="{{ route('admin.catalog.categories.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Category Name *</label>
                <input 
                    id="name" 
                    name="name" 
                    type="text" 
                    value="{{ old('name') }}" 
                    required 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                    placeholder="Electronics"
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
                    placeholder="electronics"
                />
                @error('slug')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="parent_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Parent Category</label>
                <select 
                    id="parent_id" 
                    name="parent_id" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                >
                    <option value="">— None —</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item->id }}" @selected(old('parent_id') == $item->id)>{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('parent_id')
                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2.5 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                    placeholder="Category description..."
                >{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-4 dark:bg-sf-night-900/50">
                <input 
                    id="is_active" 
                    name="is_active" 
                    type="checkbox" 
                    value="1" 
                    @checked(old('is_active', true)) 
                    class="rounded border-sf-border/60 text-sf-accent-primary focus:ring-sf-accent-primary dark:border-[rgba(114,138,221,0.35)]" 
                />
                <label for="is_active" class="text-sm font-medium text-slate-700 dark:text-slate-200">Visible in storefront</label>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button 
                    type="submit" 
                    class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                >
                    Save Category
                </button>
                <a 
                    href="{{ route('admin.catalog.categories.index') }}" 
                    class="rounded-lg border border-sf-border/60 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
