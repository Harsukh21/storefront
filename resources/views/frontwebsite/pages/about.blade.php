@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sf-accent-secondary">About</p>
        <h1 class="mt-4 text-4xl font-bold text-slate-900 dark:text-slate-100">Crafting immersive commerce experiences</h1>
        <p class="mt-6 max-w-2xl text-lg text-slate-600 dark:text-slate-300">{{ config('app.name', 'StoreFront') }} blends aesthetic storytelling with powerful infrastructure so modern retailers can launch, scale, and differentiate without sacrificing performance.</p>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto grid max-w-5xl gap-10 px-6 md:grid-cols-2">
        <article class="front-card p-8">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Our Mission</h2>
            <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">We believe every brand deserves a storefront that feels bespoke. Our platform empowers teams to orchestrate product discovery, merchandising, and checkout from a unified toolkit backed by real-time analytics.</p>
        </article>
        <article class="front-card p-8">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">What Drives Us</h2>
            <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Reliability, customization, and velocity. We obsess over reducing friction from ideation to sale, giving merchandisers, marketers, and developers a shared language that unlocks creativity.</p>
        </article>
    </div>
</section>

<section class="bg-sf-surface py-16 transition-colors dark:bg-sf-night-800">
    <div class="mx-auto max-w-5xl px-6">
        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Core Values</h2>
        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach ([
                ['title' => 'Human-Centered', 'copy' => 'We design with empathy, ensuring every feature elevates the shopper and merchant experience.'],
                ['title' => 'Performance Obsessed', 'copy' => 'Speed is emotional. We deliver millisecond responses that keep customers engaged.'],
                ['title' => 'Evolutionary', 'copy' => 'Commerce evolves quickly. We iterate in public, learning alongside our partners.'],
            ] as $value)
                <article class="front-card p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $value['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $value['copy'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endsection
