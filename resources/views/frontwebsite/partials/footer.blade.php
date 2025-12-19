<footer id="contact" class="border-t border-sf-border bg-sf-surface/70 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-12 md:grid-cols-4">
        <div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-sf-accent-primary">{{ config('app.name', 'StoreFront') }}</h3>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300">Premium shopping experiences crafted with a neon sci-fi aesthetic and modern performance.</p>
        </div>
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-wide text-sf-accent-secondary">Explore</h4>
            <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                <li><a class="transition hover:text-sf-accent-primary" href="{{ route('front.home') }}">Home</a></li>
                <li><a class="transition hover:text-sf-accent-primary" href="{{ route('front.about') }}">About</a></li>
                <li><a class="transition hover:text-sf-accent-primary" href="{{ route('front.contact') }}">Contact</a></li>
                <li><a class="transition hover:text-sf-accent-primary" href="{{ route('front.products') }}">Products</a></li>
                <li><a class="transition hover:text-sf-accent-primary" href="{{ route('front.home') }}#catalog">Catalog</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-wide text-sf-accent-secondary">Company</h4>
            <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                <li><a class="transition hover:text-sf-accent-primary" href="{{ route('front.privacy') }}">Privacy Policy</a></li>
                <li><a class="transition hover:text-sf-accent-primary" href="{{ route('front.cookie') }}">Cookie Policy</a></li>
                <li><a class="transition hover:text-sf-accent-primary" href="#">Terms of Service</a></li>
                <li><a class="transition hover:text-sf-accent-primary" href="{{ route('front.contact') }}">Support</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-wide text-sf-accent-secondary">Stay Updated</h4>
            <form class="mt-3 flex flex-col gap-3">
                <input type="email" placeholder="Email address" class="rounded-md border border-sf-border bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-sf-accent-secondary focus:outline-none focus:ring-2 focus:ring-sf-accent-secondary/40 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100 dark:placeholder-slate-400" />
                <button type="button" class="rounded-md bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-sf-night-900 transition hover:opacity-90">Subscribe</button>
            </form>
        </div>
    </div>
    <div class="border-t border-sf-border bg-white/60 py-4 dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-900">
        <p class="text-center text-xs text-sf-accent-secondary/80 dark:text-sf-accent-secondary/70">© {{ date('Y') }} {{ config('app.name', 'StoreFront') }}. All rights reserved.</p>
    </div>
</footer>
