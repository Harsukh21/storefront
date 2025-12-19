@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <a href="{{ route('front.brands') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">← All brands</a>
        <h1 class="mt-4 text-4xl font-bold text-slate-900 dark:text-slate-100">{{ $brand->name }}</h1>
        @if($brand->description)
            <p class="mt-4 max-w-3xl text-lg text-slate-600 dark:text-slate-300">{{ $brand->description }}</p>
        @endif
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-6xl px-6">
        @if($products->count())
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($products as $product)
                    @include('frontwebsite.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-12">{{ $products->links() }}</div>
        @else
            <p class="rounded-2xl border border-dashed border-sf-border/60 px-6 py-8 text-center text-sm text-slate-500 dark:border-[rgba(114,138,221,0.25)] dark:text-slate-300">No products currently attributed to this brand.</p>
        @endif
    </div>
</section>
@endsection
