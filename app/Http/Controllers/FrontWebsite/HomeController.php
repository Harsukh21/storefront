<?php

namespace App\Http\Controllers\FrontWebsite;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __invoke()
    {
        // Get featured products
        $featuredProducts = CacheService::getFeaturedProducts(6);

        // Get statistics
        $stats = [
            'total_products' => DB::table('products')->where('is_active', true)->count(),
            'total_categories' => DB::table('categories')->where('is_active', true)->count(),
            'total_brands' => DB::table('brands')->where('is_active', true)->count(),
        ];

        // Get top categories with product counts for catalog preview
        $topCategories = DB::table('categories')
            ->leftJoin('products', function ($join) {
                $join->on('products.category_id', '=', 'categories.id')
                    ->where('products.is_active', true);
            })
            ->where('categories.is_active', true)
            ->groupBy('categories.id', 'categories.name', 'categories.slug', 'categories.description')
            ->select(
                'categories.id',
                'categories.name',
                'categories.slug',
                'categories.description',
                DB::raw('COUNT(products.id) as product_count')
            )
            ->havingRaw('COUNT(products.id) > 0')
            ->orderByDesc('product_count')
            ->limit(3)
            ->get();

        // Get new arrivals (latest products)
        $newArrivals = DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->select(
                'products.id',
                'products.slug',
                'products.name',
                'products.short_description',
                'products.price',
                'products.is_featured',
                'products.created_at',
                'categories.name as category_name',
                'brands.name as brand_name'
            )
            ->where('products.is_active', true)
            ->orderByDesc('products.created_at')
            ->limit(3)
            ->get();

        // Get popular brands (brands with most products)
        $popularBrands = DB::table('brands')
            ->leftJoin('products', function ($join) {
                $join->on('products.brand_id', '=', 'brands.id')
                    ->where('products.is_active', true);
            })
            ->where('brands.is_active', true)
            ->groupBy('brands.id', 'brands.name', 'brands.slug')
            ->select(
                'brands.id',
                'brands.name',
                'brands.slug',
                DB::raw('COUNT(products.id) as product_count')
            )
            ->havingRaw('COUNT(products.id) > 0')
            ->orderByDesc('product_count')
            ->limit(6)
            ->get();

        return view('frontwebsite.pages.home', [
            'featuredProducts' => $featuredProducts,
            'stats' => $stats,
            'topCategories' => $topCategories,
            'newArrivals' => $newArrivals,
            'popularBrands' => $popularBrands,
        ]);
    }
}
