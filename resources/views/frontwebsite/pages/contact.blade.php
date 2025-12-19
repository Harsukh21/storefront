@extends('frontwebsite.layouts.app')

@section('page')
<section class="front-hero-gradient border-b border-sf-border/60 dark:border-[rgba(114,138,221,0.25)]">
    <div class="mx-auto max-w-5xl px-6 py-20">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-sf-accent-secondary">Contact</p>
        <h1 class="mt-4 text-4xl font-bold text-slate-900 dark:text-slate-100">We would love to hear from you</h1>
        <p class="mt-6 max-w-2xl text-lg text-slate-600 dark:text-slate-300">Reach the {{ config('app.name', 'StoreFront') }} team for product questions, partnership opportunities, or support. We respond within one business day.</p>
    </div>
</section>

<section class="bg-white py-16 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto grid max-w-5xl gap-12 px-6 lg:grid-cols-[2fr,1fr]">
        <form class="front-card p-8 space-y-6">
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Full Name</label>
                <input id="name" type="text" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" placeholder="Jane Doe" />
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Email</label>
                <input id="email" type="email" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" placeholder="hi@example.com" />
            </div>
            <div>
                <label for="topic" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Topic</label>
                <select id="topic" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100">
                    <option>Product Inquiry</option>
                    <option>Partnerships</option>
                    <option>Support</option>
                    <option>Billing</option>
                </select>
            </div>
            <div>
                <label for="message" class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Message</label>
                <textarea id="message" rows="5" class="mt-2 w-full rounded-md border border-sf-border bg-white px-3 py-2 text-sm focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" placeholder="Tell us how we can help"></textarea>
            </div>
            <button type="button" class="rounded-full bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-3 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">Send Message</button>
        </form>

        <aside class="front-card p-8 space-y-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">HQ</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">123 Neon Avenue<br>Suite 500<br>San Francisco, CA 94105</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Contact</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">support@storefront.local<br>+1 (555) 123-4567</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Hours</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Monday–Friday, 9am to 6pm PT</p>
            </div>
        </aside>
    </div>
</section>
@endsection
