@extends('frontwebsite.layouts.app')

@section('head')
    <title>{{ config('app.name') }} — About Us</title>
@endsection

@section('page')
<section class="front-hero-gradient py-20">
    <div class="mx-auto max-w-5xl px-6">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-slate-100">About {{ config('app.name') }}</h1>
        <p class="mt-4 text-lg text-slate-600 dark:text-slate-300">We build immersive commerce experiences that combine performance engineering with neon-futuristic aesthetics inspired by leading esports brands.</p>
    </div>
</section>
<section class="bg-white py-16 dark:bg-sf-night-900">
    <div class="mx-auto grid max-w-5xl gap-10 px-6 md:grid-cols-2">
        <div class="front-card p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Our Mission</h2>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Deliver a storefront platform that empowers merchandisers to launch, iterate, and scale without sacrificing visual storytelling or checkout speed.</p>
        </div>
        <div class="front-card p-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">Our Vision</h2>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">To become the go-to commerce engine for gaming and lifestyle brands seeking bold design systems, data-rich merchandising, and frictionless purchase flows.</p>
        </div>
    </div>
</section>
@endsection
