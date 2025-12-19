@extends('layouts.user')

@section('content')
<div class="user-shell px-6 py-10">
    <div class="mx-auto max-w-4xl">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-sf-accent-primary">My Wishlist</h1>
                <p class="text-sm text-slate-600 dark:text-slate-300">Saved products for later inspiration.</p>
            </div>
            <a href="{{ route('front.products') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Continue shopping →</a>
        </div>

        <div class="mt-6 space-y-4">
            @forelse($items as $item)
                <article class="user-card flex items-center justify-between gap-4 p-5">
                    <div>
                        <a href="{{ route('front.products.show', $item->slug) }}" class="text-lg font-semibold text-slate-900 dark:text-sf-accent-primary">{{ $item->name }}</a>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $item->short_description ?? 'Discover more details on the product page.' }}</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">${{ number_format($item->price, 2) }}</p>
                    </div>
                    <form method="POST" action="{{ route('user.wishlist.items.destroy', $item->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-full border border-red-400 px-4 py-2 text-sm font-semibold text-red-400 transition hover:bg-red-400/10">Remove</button>
                    </form>
                </article>
            @empty
                <p class="user-card px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-300">Your wishlist is empty. Explore the storefront to add favorites.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
