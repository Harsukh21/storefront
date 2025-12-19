@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sf-accent-secondary">Categories</p>
        <h1 class="mt-4 text-4xl font-bold text-slate-900 dark:text-slate-100">Explore by Category</h1>
        <p class="mt-6 max-w-3xl text-lg text-slate-600 dark:text-slate-300">Jump into curated groupings and discover products grouped by experience, use-case, and vibe.</p>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto grid max-w-6xl gap-6 px-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($categories as $category)
            <article class="front-card p-6">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $category->name }}</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $category->description ?? 'Merchandising copy coming soon.' }}</p>
                <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">{{ $category->product_count }} {{ \Illuminate\Support\Str::plural('product', $category->product_count) }}</p>
                <a href="{{ route('front.categories.show', $category->slug) }}" class="mt-4 inline-flex items-center text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Browse →</a>
            </article>
        @empty
            <p class="rounded-2xl border border-dashed border-sf-border/60 px-6 py-8 text-center text-sm text-slate-500 dark:border-[rgba(114,138,221,0.25)] dark:text-slate-300">Categories will appear here once configured.</p>
        @endforelse
    </div>
</section>
@endsection
