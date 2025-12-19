@php
    $slug = $product->slug ?? '#';
    $name = $product->name ?? 'Product';
    $price = $product->price ?? null;
    $shortDescription = $product->short_description ?? null;
    $brandName = $product->brand_name ?? null;
    $categoryName = $product->category_name ?? null;
    $isFeatured = $product->is_featured ?? false;
    $image = $product->image ?? null;
    $imageUrl = $image && isset($image->file_path) ? $image->file_path : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80';
    $imageAlt = $image && isset($image->alt_text) ? $image->alt_text : ($name ?? 'Product image');
@endphp

<article class="front-card group overflow-hidden p-0">
    <!-- Product Image -->
    <div class="relative aspect-square overflow-hidden bg-slate-100 dark:bg-sf-night-800">
        <a href="{{ $slug !== '#' ? route('front.products.show', $slug) : '#' }}" class="block h-full w-full">
            <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
        </a>
        @if($isFeatured)
            <span class="absolute top-3 right-3 sf-badge-glow rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide">Featured</span>
        @endif
    </div>
    
    <!-- Product Info -->
    <div class="p-6">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-slate-900 transition-colors group-hover:text-sf-accent-primary dark:text-slate-100">
                    <a href="{{ $slug !== '#' ? route('front.products.show', $slug) : '#' }}">{{ $name }}</a>
                </h3>
                <div class="mt-1 flex flex-wrap gap-2 text-xs text-slate-500 dark:text-slate-400">
                    @if($brandName)
                        <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-secondary/10 px-2 py-0.5 text-[11px] font-semibold text-sf-accent-secondary">{{ $brandName }}</span>
                    @endif
                    @if($categoryName)
                        <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-2 py-0.5 text-[11px] font-semibold text-sf-accent-primary">{{ $categoryName }}</span>
                    @endif
                </div>
            </div>
        </div>
        <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 line-clamp-2">{{ $shortDescription ?? 'More details coming soon.' }}</p>
    <p class="mt-5 text-base font-semibold text-slate-900 dark:text-slate-200">
        @if(!is_null($price))
            ${{ number_format($price, 2) }}
        @else
            Coming soon
        @endif
    </p>
    <div class="mt-6 flex flex-col gap-3">
        <div class="flex items-center justify-between text-sm">
            <a href="{{ $slug !== '#' ? route('front.products.show', $slug) : '#' }}" class="font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View details →</a>
            @if ($slug !== '#')
                @auth
                    <form method="POST" action="{{ route('user.wishlist.items.store') }}" class="inline">
                        @csrf
                        <input type="hidden" name="product_slug" value="{{ $slug }}" />
                        <button type="submit" class="text-slate-500 transition hover:text-sf-accent-secondary dark:text-slate-300">Add to wishlist</button>
                    </form>
                @else
                    <a href="{{ route('user.login') }}" class="text-slate-500 transition hover:text-sf-accent-secondary dark:text-slate-300">Sign in to save</a>
                @endauth
            @endif
        </div>
        @if ($slug !== '#' && isset($product->id) && $product->id > 0)
            <form method="POST" action="{{ route('front.cart.store') }}" class="w-full">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <button type="submit" class="w-full rounded-full border border-sf-accent-primary px-4 py-2 text-sm font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/10">Add to Cart</button>
            </form>
        @endif
    </div>
</article>
