<?php

namespace App\Http\Controllers\FrontWebsite;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;

class BrandDirectoryController extends Controller
{
    public function index()
    {
        $brands = CacheService::getBrands();

        return view('frontwebsite.pages.brands.index', compact('brands'));
    }

    public function show(string $slug)
    {
        $brand = DB::table('brands')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$brand) {
            abort(404);
        }

        $products = DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->select(
                'products.id',
                'products.slug',
                'products.name',
                'products.short_description',
                'products.price',
                'products.is_featured',
                'categories.name as category_name'
            )
            ->where('products.is_active', true)
            ->where('products.brand_id', $brand->id)
            ->orderByDesc('products.created_at')
            ->paginate(12)
            ->withQueryString();

        $products->getCollection()->transform(function ($productItem) use ($brand) {
            $productItem->brand_name = $brand->name;
            return $productItem;
        });

        return view('frontwebsite.pages.brands.show', [
            'brand' => $brand,
            'products' => $products,
        ]);
    }
}
