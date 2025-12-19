@extends('frontwebsite.layouts.app')

@section('page')
<!-- Page Header -->
<section class="border-b border-sf-border/60 bg-white py-12 dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-900">
    <div class="mx-auto max-w-7xl px-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100 md:text-4xl">Products</h1>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                    <span id="products-count-text">
                        @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->total() > 0)
                            Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
                        @else
                            Browse our collection
                        @endif
                    </span>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <select id="sort-select" class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">
                    <option value="featured" {{ $filters['sort'] === 'featured' ? 'selected' : '' }}>Featured First</option>
                    <option value="newest" {{ $filters['sort'] === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="price_low" {{ $filters['sort'] === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_high" {{ $filters['sort'] === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc" {{ $filters['sort'] === 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                    <option value="name_desc" {{ $filters['sort'] === 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Sticky Filter Bar -->
<div id="filter-bar" class="sticky top-0 z-40 border-b border-sf-border/60 bg-white/95 backdrop-blur-sm dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-900/95">
    <div class="mx-auto max-w-7xl px-6 py-4">
        <form id="filter-form" class="flex flex-wrap items-center gap-3">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <input type="text" id="filter-search" name="search" value="{{ $filters['search'] }}" placeholder="Search products..." class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-100" />
            </div>

            <!-- Category Filter -->
            <div class="min-w-[150px]">
                <select id="filter-category" name="category" class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-100">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" {{ $filters['category'] === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Brand Filter -->
            <div class="min-w-[150px]">
                <select id="filter-brand" name="brand" class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-100">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->slug }}" {{ $filters['brand'] === $brand->slug ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range -->
            @if($priceRange && $priceRange->min_price && $priceRange->max_price)
            <div class="flex items-center gap-2">
                <input type="number" id="filter-price-min" name="price_min" value="{{ $filters['price_min'] }}" placeholder="Min" min="{{ floor($priceRange->min_price) }}" max="{{ ceil($priceRange->max_price) }}" class="w-24 rounded-lg border border-sf-border/60 bg-white px-3 py-2 text-sm transition focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-100" />
                <span class="text-slate-500 dark:text-slate-400">-</span>
                <input type="number" id="filter-price-max" name="price_max" value="{{ $filters['price_max'] }}" placeholder="Max" min="{{ floor($priceRange->min_price) }}" max="{{ ceil($priceRange->max_price) }}" class="w-24 rounded-lg border border-sf-border/60 bg-white px-3 py-2 text-sm transition focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-100" />
            </div>
            @endif

            <!-- Featured Toggle -->
            <label class="flex items-center gap-2 whitespace-nowrap rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200 cursor-pointer">
                <input type="checkbox" id="filter-featured" name="featured" value="1" {{ $filters['featured'] ? 'checked' : '' }} class="rounded border-sf-border text-sf-accent-primary focus:ring-sf-accent-primary" />
                <span>Featured</span>
            </label>

            <!-- Clear Filters -->
            @if($filters['search'] || $filters['category'] || $filters['brand'] || $filters['price_min'] || $filters['price_max'] || $filters['featured'])
            <button type="button" id="clear-filters" class="whitespace-nowrap rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-sf-accent-secondary hover:text-sf-accent-secondary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200">
                Clear All
            </button>
            @endif
        </form>
    </div>
</div>

<!-- Products Section -->
<section class="bg-white py-12 transition-colors dark:bg-sf-night-900">
    <div class="mx-auto max-w-7xl px-6">
        <!-- Loading State -->
        <div id="products-loading" class="hidden text-center py-16">
            <div class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-sf-accent-primary border-t-transparent"></div>
            <p class="mt-4 text-sm text-slate-600 dark:text-slate-300">Loading products...</p>
        </div>

        <!-- Products Grid -->
        <div id="products-container">
            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->count())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        @include('frontwebsite.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div id="products-pagination" class="mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($fallbackProducts as $product)
                        @include('frontwebsite.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Empty State -->
        <div id="products-empty" class="hidden text-center py-16">
            <svg class="mx-auto h-20 w-20 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-6 text-xl font-semibold text-slate-900 dark:text-slate-100">No products found</h3>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Try adjusting your filters to see more results.</p>
            <button id="clear-filters-empty" class="mt-6 rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-6 py-2.5 text-sm font-semibold text-white shadow-sf-glow transition hover:opacity-90 dark:text-sf-night-900">
                Clear All Filters
            </button>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
(function() {
    let filterTimeout;
    const filterForm = document.getElementById('filter-form');
    const productsContainer = document.getElementById('products-container');
    const productsLoading = document.getElementById('products-loading');
    const productsEmpty = document.getElementById('products-empty');
    const productsPagination = document.getElementById('products-pagination');
    const sortSelect = document.getElementById('sort-select');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const clearFiltersEmptyBtn = document.getElementById('clear-filters-empty');
    const productsCountText = document.getElementById('products-count-text');

    // Apply filters function
    function applyFilters() {
        clearTimeout(filterTimeout);
        
        filterTimeout = setTimeout(() => {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();
            
            // Add sort
            if (sortSelect && sortSelect.value) {
                params.append('sort', sortSelect.value);
            }

            // Add form data
            for (const [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }

            // Show loading
            productsContainer.classList.add('hidden');
            productsLoading.classList.remove('hidden');
            productsEmpty.classList.add('hidden');

            // Fetch products
            fetch(`{{ route('front.products') }}?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                productsLoading.classList.add('hidden');
                
                if (data.products && data.products.length > 0) {
                    renderProducts(data.products);
                    renderPagination(data.pagination);
                    updateProductsCount(data.pagination);
                    productsContainer.classList.remove('hidden');
                    productsEmpty.classList.add('hidden');
                } else {
                    productsContainer.classList.add('hidden');
                    productsEmpty.classList.remove('hidden');
                }

                // Update URL without reload
                const newUrl = `{{ route('front.products') }}?${params.toString()}`;
                window.history.pushState({}, '', newUrl);

                // Update clear filters button visibility
                updateClearButton();
            })
            .catch(error => {
                console.error('Filter error:', error);
                productsLoading.classList.add('hidden');
                productsContainer.classList.remove('hidden');
            });
        }, 300); // Debounce 300ms
    }

    // Render products
    function renderProducts(products) {
        const grid = document.createElement('div');
        grid.className = 'grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4';
        
        products.forEach(product => {
            const imageUrl = product.image && product.image.file_path ? product.image.file_path : 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80';
            const imageAlt = product.image && product.image.alt_text ? escapeHtml(product.image.alt_text) : escapeHtml(product.name);
            
            const card = document.createElement('article');
            card.className = 'front-card group overflow-hidden p-0';
            card.innerHTML = `
                <!-- Product Image -->
                <div class="relative aspect-square overflow-hidden bg-slate-100 dark:bg-sf-night-800">
                    <a href="/products/${product.slug}" class="block h-full w-full">
                        <img src="${imageUrl}" alt="${imageAlt}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                    </a>
                    ${product.is_featured ? '<span class="absolute top-3 right-3 sf-badge-glow rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide">Featured</span>' : ''}
                </div>
                
                <!-- Product Info -->
                <div class="p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-slate-900 transition-colors group-hover:text-sf-accent-primary dark:text-slate-100">
                                <a href="/products/${product.slug}">${escapeHtml(product.name)}</a>
                            </h3>
                            <div class="mt-1 flex flex-wrap gap-2 text-xs text-slate-500 dark:text-slate-400">
                                ${product.brand_name ? `<span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-secondary/10 px-2 py-0.5 text-[11px] font-semibold text-sf-accent-secondary">${escapeHtml(product.brand_name)}</span>` : ''}
                                ${product.category_name ? `<span class="inline-flex items-center gap-1 rounded-full bg-sf-accent-primary/10 px-2 py-0.5 text-[11px] font-semibold text-sf-accent-primary">${escapeHtml(product.category_name)}</span>` : ''}
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 line-clamp-2">${product.short_description ? escapeHtml(product.short_description) : 'More details coming soon.'}</p>
                    <p class="mt-5 text-base font-semibold text-slate-900 dark:text-slate-200">$${parseFloat(product.price || 0).toFixed(2)}</p>
                    <div class="mt-6 flex flex-col gap-3">
                        <div class="flex items-center justify-between text-sm">
                            <a href="/products/${product.slug}" class="font-semibold text-sf-accent-primary transition hover:text-sf-accent-secondary">View details →</a>
                        </div>
                        <form method="POST" action="{{ route('front.cart.store') }}" class="w-full">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="product_id" value="${product.id}" />
                            <button type="submit" class="w-full rounded-full border border-sf-accent-primary px-4 py-2 text-sm font-semibold text-sf-accent-primary transition hover:bg-sf-accent-primary/10">Add to Cart</button>
                        </form>
                    </div>
                </div>
            `;
            grid.appendChild(card);
        });

        productsContainer.innerHTML = '';
        productsContainer.appendChild(grid);
    }

    // Render pagination
    function renderPagination(pagination) {
        if (!pagination || pagination.last_page <= 1) {
            if (productsPagination) {
                productsPagination.innerHTML = '';
            }
            return;
        }

        let paginationHTML = '<div class="flex items-center justify-center gap-2">';
        
        if (pagination.current_page > 1) {
            paginationHTML += `<a href="#" data-page="${pagination.current_page - 1}" class="rounded-lg border border-sf-border/60 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-sf-accent-primary/10 dark:border-[rgba(114,138,221,0.35)] dark:text-slate-300">Previous</a>`;
        }

        for (let i = 1; i <= pagination.last_page; i++) {
            if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)) {
                paginationHTML += `<a href="#" data-page="${i}" class="rounded-lg px-4 py-2 text-sm font-semibold transition ${i === pagination.current_page ? 'bg-sf-accent-primary text-white' : 'text-slate-700 hover:bg-sf-accent-primary/10 dark:text-slate-300'}">${i}</a>`;
            } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
                paginationHTML += '<span class="px-2 text-slate-400">...</span>';
            }
        }

        if (pagination.current_page < pagination.last_page) {
            paginationHTML += `<a href="#" data-page="${pagination.current_page + 1}" class="rounded-lg border border-sf-border/60 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-sf-accent-primary/10 dark:border-[rgba(114,138,221,0.35)] dark:text-slate-300">Next</a>`;
        }

        paginationHTML += '</div>';

        if (productsPagination) {
            productsPagination.innerHTML = paginationHTML;
            
            // Add click handlers
            productsPagination.querySelectorAll('a[data-page]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const page = link.getAttribute('data-page');
                    const formData = new FormData(filterForm);
                    const params = new URLSearchParams();
                    params.append('page', page);
                    for (const [key, value] of formData.entries()) {
                        if (value) params.append(key, value);
                    }
                    if (sortSelect && sortSelect.value) {
                        params.append('sort', sortSelect.value);
                    }
                    window.location.href = `{{ route('front.products') }}?${params.toString()}`;
                });
            });
        }
    }

    // Update products count
    function updateProductsCount(pagination) {
        if (productsCountText && pagination) {
            const first = pagination.from || 0;
            const last = pagination.to || 0;
            const total = pagination.total || 0;
            productsCountText.textContent = `Showing ${first}-${last} of ${total} products`;
        }
    }

    // Update clear button visibility
    function updateClearButton() {
        const formData = new FormData(filterForm);
        const hasFilters = Array.from(formData.entries()).some(([key, value]) => {
            if (key === 'featured') return value === '1';
            return value && value !== '';
        });
        
        if (clearFiltersBtn) {
            if (hasFilters) {
                clearFiltersBtn.classList.remove('hidden');
            } else {
                clearFiltersBtn.classList.add('hidden');
            }
        }
    }

    // Clear filters
    function clearFilters() {
        filterForm.reset();
        if (sortSelect) {
            sortSelect.value = 'featured';
        }
        applyFilters();
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Event listeners
    if (filterForm) {
        filterForm.addEventListener('change', applyFilters);
        filterForm.addEventListener('input', (e) => {
            if (e.target.type === 'text' || e.target.type === 'number') {
                applyFilters();
            }
        });
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', applyFilters);
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearFilters);
    }

    if (clearFiltersEmptyBtn) {
        clearFiltersEmptyBtn.addEventListener('click', clearFilters);
    }

    // Initial clear button state
    updateClearButton();
})();
</script>
@endsection
