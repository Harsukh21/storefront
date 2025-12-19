@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient relative overflow-hidden">
    <div class="mx-auto flex max-w-6xl flex-col gap-10 px-6 py-24 md:flex-row md:items-center">
        <div class="md:w-1/2">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sf-accent-secondary">Welcome to {{ config('app.name', 'StoreFront') }}</p>
            <h1 class="mt-4 text-4xl font-bold leading-tight text-slate-900 transition-colors dark:text-slate-100 md:text-5xl">Discover {{ number_format($stats['total_products']) }}+ Premium Products</h1>
            <p class="mt-6 text-lg text-slate-600 dark:text-slate-300">Shop from {{ $stats['total_categories'] }} categories and {{ $stats['total_brands'] }} trusted brands. Find everything you need with our curated selection of quality products.</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('front.products') }}" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Shop Now</a>
                <a href="{{ route('user.login') }}" class="rounded-full border border-sf-accent-primary/70 px-6 py-3 text-sm font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/10">Sign In</a>
            </div>
            <div class="mt-8 flex flex-wrap gap-6 text-sm">
                <div>
                    <span class="text-2xl font-bold text-sf-accent-primary">{{ number_format($stats['total_products']) }}</span>
                    <span class="ml-2 text-slate-600 dark:text-slate-300">Products</span>
                </div>
                <div>
                    <span class="text-2xl font-bold text-sf-accent-primary">{{ number_format($stats['total_categories']) }}</span>
                    <span class="ml-2 text-slate-600 dark:text-slate-300">Categories</span>
                </div>
                <div>
                    <span class="text-2xl font-bold text-sf-accent-primary">{{ number_format($stats['total_brands']) }}</span>
                    <span class="ml-2 text-slate-600 dark:text-slate-300">Brands</span>
                </div>
            </div>
        </div>
        <div class="md:w-1/2">
            <div class="relative mx-auto max-w-md">
                <div class="absolute -inset-6 rounded-full bg-sf-accent-primary/20 blur-3xl dark:bg-sf-accent-primary/25"></div>
                @if($featuredProducts->isNotEmpty())
                    @php $firstProduct = $featuredProducts->first(); @endphp
                    <div class="relative rounded-3xl border border-sf-accent-primary/40 bg-gradient-to-br from-sf-accent-primary/10 to-sf-accent-secondary/10 p-8 shadow-2xl shadow-sf-glow">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-slate-100">{{ $firstProduct->name }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $firstProduct->short_description }}</p>
                        <p class="mt-4 text-2xl font-bold text-sf-accent-primary">${{ number_format($firstProduct->price, 2) }}</p>
                        <a href="{{ route('front.products.show', $firstProduct->slug) }}" class="mt-4 inline-block rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90 dark:text-sf-night-900">View Product</a>
                    </div>
                @else
                    <img src="https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=960&q=80" alt="Shopping experience" class="relative rounded-3xl border border-sf-accent-primary/40 shadow-2xl shadow-sf-glow" />
                @endif
            </div>
        </div>
    </div>
</section>

@if($featuredProducts->count())
<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-6xl px-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sf-accent-secondary">Highlighted Releases</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">Featured products</h2>
            </div>
            <a href="{{ route('front.products') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View all products →</a>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach($featuredProducts as $product)
                @include('frontwebsite.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<section id="features" class="bg-sf-surface py-24 transition-colors dark:bg-sf-night-800">
    <div class="mx-auto max-w-6xl px-6">
        <div class="mb-12 flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sf-accent-secondary">Features</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">Engineered for modern commerce teams</h2>
            </div>
            <a href="#contact" class="rounded-full border border-sf-accent-secondary/70 px-5 py-2 text-sm font-semibold text-sf-accent-secondary transition hover:bg-sf-accent-secondary/10">Talk to us</a>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ([
                [
                    'title' => 'Real-Time Merchandising',
                    'copy' => 'Update collections, pricing, and availability instantly with streamlined admin tools.',
                ],
                [
                    'title' => 'Performance Optimized',
                    'copy' => 'Achieve sub-second page loads with server-side rendering and aggressive caching.',
                ],
                [
                    'title' => 'Frictionless Checkout',
                    'copy' => 'Deliver a smooth purchasing flow with multiple payment options and saved profiles.',
                ],
            ] as $feature)
                <article class="front-card p-6">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-sf-accent-primary to-sf-accent-secondary text-sm font-bold text-sf-night-900">★</span>
                    <h3 class="mt-6 text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $feature['title'] }}</h3>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">{{ $feature['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

@if($topCategories->isNotEmpty())
<section id="catalog" class="bg-white py-24 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-6xl px-6">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sf-accent-secondary">Shop by Category</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">Explore our top categories</h2>
            </div>
            <a href="{{ route('front.categories') }}" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-5 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">View All Categories</a>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-3">
            @foreach($topCategories as $category)
                <article class="group front-card relative overflow-hidden">
                    <div class="aspect-[4/5] overflow-hidden bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20">
                        <div class="flex h-full items-center justify-center">
                            <span class="text-6xl font-bold text-sf-accent-primary/30">{{ substr($category->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $category->name }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                            {{ $category->description ?: 'Discover our curated selection of ' . strtolower($category->name) . ' products.' }}
                        </p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $category->product_count }} {{ $category->product_count === 1 ? 'product' : 'products' }}</span>
                            <a href="{{ route('front.categories.show', $category->slug) }}" class="inline-flex items-center text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View Category →</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($newArrivals->isNotEmpty())
<section class="bg-sf-surface py-24 transition-colors dark:bg-sf-night-800">
    <div class="mx-auto max-w-6xl px-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sf-accent-secondary">New Arrivals</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">Latest products</h2>
            </div>
            <a href="{{ route('front.products') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View all products →</a>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach($newArrivals as $product)
                @include('frontwebsite.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

@if($popularBrands->isNotEmpty())
<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-6xl px-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-sf-accent-secondary">Popular Brands</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900 dark:text-slate-100">Shop by brand</h2>
            </div>
            <a href="{{ route('front.brands') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View all brands →</a>
        </div>
        <div class="mt-10 grid gap-4 md:grid-cols-3 lg:grid-cols-6">
            @foreach($popularBrands as $brand)
                <a href="{{ route('front.brands.show', $brand->slug) }}" class="group front-card flex flex-col items-center justify-center p-6 text-center transition hover:scale-105">
                    <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-sf-accent-primary/20 to-sf-accent-secondary/20 text-2xl font-bold text-sf-accent-primary">
                        {{ substr($brand->name, 0, 1) }}
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $brand->name }}</h3>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $brand->product_count }} {{ $brand->product_count === 1 ? 'product' : 'products' }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
