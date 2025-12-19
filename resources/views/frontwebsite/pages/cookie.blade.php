@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sf-accent-secondary">Cookie Policy</p>
        <h1 class="mt-4 text-4xl font-bold text-slate-900 dark:text-slate-100">Optimizing experiences responsibly</h1>
        <p class="mt-6 max-w-2xl text-lg text-slate-600 dark:text-slate-300">Learn how {{ config('app.name', 'StoreFront') }} uses cookies and similar technologies to keep sessions secure and enhance store performance.</p>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-5xl space-y-8 px-6">
        <article class="front-card p-8">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Essential Cookies</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Required for login sessions, cart persistence, and fraud prevention. These cookies cannot be disabled because the storefront would no longer function correctly.</p>
        </article>
        <article class="front-card p-8">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Analytics & Performance</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Aggregated analytics help us understand which collections resonate and where shoppers drop off. We anonymize IP addresses and store data for 13 months.</p>
        </article>
        <article class="front-card p-8">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Preference Management</h2>
            <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-300">You can adjust cookie preferences at any time within your account settings or by emailing privacy@storefront.local. Opting out of analytics will not impact checkout.</p>
        </article>
    </div>
</section>
@endsection
