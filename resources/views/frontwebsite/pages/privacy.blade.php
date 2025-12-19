@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sf-accent-secondary">Privacy Policy</p>
        <h1 class="mt-4 text-4xl font-bold text-slate-900 dark:text-slate-100">Your privacy is mission critical</h1>
        <p class="mt-6 max-w-2xl text-lg text-slate-600 dark:text-slate-300">This policy explains how {{ config('app.name', 'StoreFront') }} collects, processes, and safeguards personal data across the storefront experience.</p>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-5xl space-y-10 px-6">
        <article class="front-card p-8">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Information We Collect</h2>
            <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">We gather account details, order history, browsing events, and support interactions to deliver tailored commerce experiences. Payment data is processed via certified providers; we only store tokens.</p>
        </article>
        <article class="front-card p-8">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">How We Use Data</h2>
            <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Data powers authentication, personalization, analytics, and compliance. We never sell customer information and only share with approved processors who meet strict security requirements.</p>
        </article>
        <article class="front-card p-8">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Your Rights</h2>
            <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Customers can request exports, edits, or deletion of data at any time by contacting privacy@storefront.local. We respond within 30 days and honor regional regulations such as GDPR and CCPA.</p>
        </article>
    </div>
</section>
@endsection
