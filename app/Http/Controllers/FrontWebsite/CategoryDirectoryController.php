<?php

namespace App\Http\Controllers\FrontWebsite;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Support\Facades\DB;

class CategoryDirectoryController extends Controller
{
    public function index()
    {
        $categories = CacheService::getCategories();

        return view('frontwebsite.pages.categories.index', compact('categories'));
    }

    public function show(string $slug)
    {
        $category = DB::table('categories')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$category) {
            abort(404);
        }

        $products = DB::table('products')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->select(
                'products.id',
                'products.slug',
                'products.name',
                'products.short_description',
                'products.price',
                'products.is_featured',
                'brands.name as brand_name'
            )
            ->where('products.is_active', true)
            ->where('products.category_id', $category->id)
            ->orderByDesc('products.created_at')
            ->paginate(12)
            ->withQueryString();

        $products->getCollection()->transform(function ($productItem) use ($category) {
            $productItem->category_name = $category->name;
            return $productItem;
        });

        return view('frontwebsite.pages.categories.show', [
            'category' => $category,
            'products' => $products,
        ]);
    }
}
