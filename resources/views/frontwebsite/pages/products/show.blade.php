@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <a href="{{ route('front.products') }}" class="text-sm font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">← Back to products</a>
        <div class="mt-6 flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
            @if($product->brand_name)
                <span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-secondary/10 px-3 py-1 font-semibold text-sf-accent-secondary">{{ $product->brand_name }}</span>
            @endif
            @if($product->category_name)
                <a href="{{ route('front.categories.show', $product->category_slug ?? '#') }}" class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-3 py-1 font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/15">{{ $product->category_name }}</a>
            @endif
        </div>
        <h1 class="mt-4 text-4xl font-bold text-slate-900 dark:text-slate-100">{{ $product->name }}</h1>
        @if($product->short_description)
            <p class="mt-4 max-w-3xl text-lg text-slate-600 dark:text-slate-300">{{ $product->short_description }}</p>
        @endif
        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
            <span class="text-2xl font-semibold text-slate-900 dark:text-slate-100">
                @if(isset($product->price))
                    ${{ number_format($product->price, 2) }}
                @else
                    Pricing coming soon
                @endif
            </span>
            <span>
                @php $rating = $averageRating ? number_format($averageRating, 1) : '—'; @endphp
                Rating: <span class="font-semibold text-sf-accent-primary">{{ $rating }}</span> ({{ $reviews->count() }} {{ \Illuminate\Support\Str::plural('review', $reviews->count()) }})
            </span>
        </div>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto grid max-w-5xl gap-10 px-6 lg:grid-cols-[3fr,2fr]">
        <div class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
                <img src="{{ $images->first()->file_path ?? '' }}" alt="{{ $images->first()->alt_text ?? $product->name }}" class="h-80 w-full object-cover" />
            </div>
            @if($images->count() > 1)
                <div class="grid grid-cols-4 gap-3">
                    @foreach($images->slice(1, 4) as $image)
                        <img src="{{ $image->file_path }}" alt="{{ $image->alt_text ?? $product->name }}" class="h-20 w-full rounded-xl border border-sf-border/60 object-cover dark:border-[rgba(114,138,221,0.25)]" />
                    @endforeach
                </div>
            @endif
            <article class="front-card p-8">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Product Overview</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                    {{ $product->description ?? 'Detailed product information will be available soon. This placeholder ensures the layout is ready for long-form content, specifications, and story-driven merchandising.' }}
                </p>
            </article>
        </div>

        <aside class="front-card p-8 space-y-4">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Purchase Options</h2>
            <form method="POST" action="{{ route('front.cart.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                <button type="submit" class="w-full rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Add to cart</button>
            </form>
            @auth
                <form method="POST" action="{{ route('user.wishlist.items.store') }}">
                    @csrf
                    <input type="hidden" name="product_slug" value="{{ $product->slug }}" />
                    <button type="submit" class="w-full rounded-full border border-sf-accent-primary px-6 py-3 text-sm font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/10">Add to wishlist</button>
                </form>
            @else
                <a href="{{ route('user.login') }}" class="block w-full rounded-full border border-sf-accent-primary px-6 py-3 text-center text-sm font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/10">Sign in to save</a>
            @endauth
            <div class="pt-4 text-xs text-slate-500 dark:text-slate-300">Secure checkout. Ships within 2-3 business days.</div>

            <div class="border-t border-sf-border/60 pt-4 text-sm dark:border-[rgba(114,138,221,0.25)]">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Product details</h3>
                <dl class="mt-2 space-y-1 text-slate-600 dark:text-slate-300">
                    @if($product->sku)
                        <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                            <dt>SKU</dt><dd>{{ $product->sku }}</dd>
                        </div>
                    @endif
                    @if($product->weight)
                        <div class="flex justify-between text-xs text-slate-500 dark:text-slate-400">
                            <dt>Weight</dt><dd>{{ $product->weight }} kg</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </aside>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-5xl px-6">
        <div class="flex flex-col gap-6 lg:flex-row">
            <div class="flex-1">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Customer Reviews</h2>
                <div class="mt-4 space-y-4">
                    @forelse($reviews as $review)
                        <article class="rounded-xl border border-sf-border/60 p-4 text-sm dark:border-[rgba(114,138,221,0.25)]">
                            <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $review->user_name ?? 'Customer' }}</span>
                                <span>{{ $review->created_at ? \Carbon\Carbon::parse($review->created_at)->diffForHumans() : '' }}</span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-sf-accent-primary">Rating: {{ $review->rating }}/5</span>
                                @if(isset($review->helpful_count) && $review->helpful_count > 0)
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $review->helpful_count }} {{ \Illuminate\Support\Str::plural('person', $review->helpful_count) }} found this helpful</span>
                                @endif
                            </div>
                            @if($review->title)
                                <h3 class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $review->title }}</h3>
                            @endif
                            @if($review->body)
                                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $review->body }}</p>
                            @endif
                            @auth
                                <div class="mt-3 flex gap-2">
                                    <form method="POST" action="{{ route('user.reviews.helpful.store', $review->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="is_helpful" value="1" />
                                        <button type="submit" class="text-xs font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">Helpful</button>
                                    </form>
                                </div>
                            @endauth
                        </article>
                    @empty
                        <p class="rounded-xl border border-dashed border-sf-border/60 px-4 py-6 text-sm text-slate-500 dark:border-[rgba(114,138,221,0.25)] dark:text-slate-300">No reviews yet. Be the first to review this product.</p>
                    @endforelse
                </div>
            </div>
            <div class="w-full max-w-md rounded-2xl border border-sf-border/60 bg-sf-bg-secondary p-6 text-sm dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-800">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Leave a review</h3>
                @auth
                    <form method="POST" action="{{ route('user.products.reviews.store', $product->slug) }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <label for="rating" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Rating</label>
                            <select id="rating" name="rating" class="mt-2 w-full rounded-md">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} Star{{ $i === 1 ? '' : 's' }}</option>
                                @endfor
                            </select>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="title" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Title</label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}" class="mt-2 w-full rounded-md" />
                        </div>
                        <div>
                            <label for="body" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Comments</label>
                            <textarea id="body" name="body" rows="4" class="mt-2 w-full rounded-md">{{ old('body') }}</textarea>
                        </div>
                        <button type="submit" class="w-full rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Submit review</button>
                    </form>
                @else
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-300">Please <a href="{{ route('user.login') }}" class="font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">sign in</a> to leave a review.</p>
                @endauth
            </div>
        </div>
    </div>
</section>

@if(isset($questions) && $questions->count() > 0)
<section class="bg-sf-surface py-16 transition-colors dark:bg-sf-night-800">
    <div class="mx-auto max-w-5xl px-6">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Questions & Answers</h2>
        <div class="space-y-4">
            @foreach($questions as $question)
                <div class="front-card p-6">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $question->question }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Asked by {{ $question->user_name ?? 'Customer' }} on {{ \Carbon\Carbon::parse($question->created_at)->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                    @if(isset($question->answers) && $question->answers->count() > 0)
                        <div class="mt-4 space-y-3 border-t border-sf-border pt-4 dark:border-[rgba(114,138,221,0.25)]">
                            @foreach($question->answers as $answer)
                                <div class="rounded-lg bg-sf-bg-secondary p-3 dark:bg-sf-night-900">
                                    <p class="text-sm text-slate-700 dark:text-slate-200">{{ $answer->answer }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        Answered by {{ $answer->admin_name ?? ($answer->user_name ?? 'Admin') }} on {{ \Carbon\Carbon::parse($answer->created_at)->format('M d, Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        @auth
            <div class="mt-6 front-card p-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Ask a Question</h3>
                <form method="POST" action="{{ route('front.products.questions.store', $product->slug) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="question" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Your Question</label>
                        <textarea id="question" name="question" rows="3" required class="w-full rounded-md text-sm"></textarea>
                        @error('question')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Submit Question</button>
                </form>
            </div>
        @else
            <div class="mt-6 front-card p-6 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-300">Please <a href="{{ route('user.login') }}" class="font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">sign in</a> to ask a question.</p>
            </div>
        @endauth
    </div>
</section>
@else
@auth
<section class="bg-sf-surface py-16 transition-colors dark:bg-sf-night-800">
    <div class="mx-auto max-w-5xl px-6">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100 mb-6">Ask a Question</h2>
        <div class="front-card p-6">
            <form method="POST" action="{{ route('front.products.questions.store', $product->slug) }}" class="space-y-4">
                @csrf
                <div>
                    <label for="question" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Your Question</label>
                    <textarea id="question" name="question" rows="3" required class="w-full rounded-md text-sm"></textarea>
                    @error('question')<p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Submit Question</button>
            </form>
        </div>
    </div>
</section>
@endauth
@endif

@if($related->count())
<section class="bg-sf-surface py-16 transition-colors dark:bg-sf-night-800">
    <div class="mx-auto max-w-6xl px-6">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Related products</h2>
        <div class="mt-8 grid gap-6 md:grid-cols-4">
            @foreach($related as $item)
                @include('frontwebsite.partials.product-card', ['product' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
