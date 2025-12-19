<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheService
{
    const CACHE_TTL = 3600; // 1 hour
    const NAVIGATION_TTL = 7200; // 2 hours

    /**
     * Get active categories with product counts (cached)
     */
    public static function getCategories()
    {
        return Cache::remember('categories:active', self::NAVIGATION_TTL, function () {
            return DB::table('categories')
                ->leftJoin('products', function ($join) {
                    $join->on('products.category_id', '=', 'categories.id')
                        ->where('products.is_active', true);
                })
                ->where('categories.is_active', true)
                ->groupBy('categories.id', 'categories.name', 'categories.slug', 'categories.description', 'categories.parent_id')
                ->select(
                    'categories.id',
                    'categories.name',
                    'categories.slug',
                    'categories.description',
                    'categories.parent_id',
                    DB::raw('COUNT(products.id) as product_count')
                )
                ->orderBy('categories.name')
                ->get();
        });
    }

    /**
     * Get active brands with product counts (cached)
     */
    public static function getBrands()
    {
        return Cache::remember('brands:active', self::NAVIGATION_TTL, function () {
            return DB::table('brands')
                ->leftJoin('products', function ($join) {
                    $join->on('products.brand_id', '=', 'brands.id')
                        ->where('products.is_active', true);
                })
                ->where('brands.is_active', true)
                ->groupBy('brands.id', 'brands.name', 'brands.slug', 'brands.description')
                ->select(
                    'brands.id',
                    'brands.name',
                    'brands.slug',
                    'brands.description',
                    DB::raw('COUNT(products.id) as product_count')
                )
                ->orderBy('brands.name')
                ->get();
        });
    }

    /**
     * Get featured products (cached)
     */
    public static function getFeaturedProducts($limit = 6)
    {
        return Cache::remember("products:featured:{$limit}", self::CACHE_TTL, function () use ($limit) {
            return DB::table('products')
                ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                ->select(
                    'products.id',
                    'products.slug',
                    'products.name',
                    'products.short_description',
                    'products.price',
                    'products.is_featured',
                    'categories.name as category_name',
                    'brands.name as brand_name'
                )
                ->where('products.is_active', true)
                ->orderByDesc('products.is_featured')
                ->orderByDesc('products.created_at')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Clear catalog cache
     */
    public static function clearCatalogCache()
    {
        Cache::forget('categories:active');
        Cache::forget('brands:active');
        Cache::forget('products:featured:6');
    }

    /**
     * Clear product-specific cache
     */
    public static function clearProductCache($productId = null)
    {
        if ($productId) {
            Cache::forget("product:{$productId}");
        }
        self::clearCatalogCache();
    }
}

