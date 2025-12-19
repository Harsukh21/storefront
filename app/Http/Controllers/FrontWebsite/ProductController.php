<?php

namespace App\Http\Controllers\FrontWebsite;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('products')
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
                'categories.slug as category_slug',
                'brands.name as brand_name',
                'brands.slug as brand_slug'
            )
            ->where('products.is_active', true);

        if ($search = $request->query('search')) {
            $query->where(function ($inner) use ($search) {
                $inner->where('products.name', 'ILIKE', "%{$search}%")
                    ->orWhere('products.short_description', 'ILIKE', "%{$search}%")
                    ->orWhere('products.sku', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->boolean('featured')) {
            $query->where('products.is_featured', true);
        }

        if ($categorySlug = $request->query('category')) {
            $categoryId = DB::table('categories')
                ->where('slug', $categorySlug)
                ->where('is_active', true)
                ->value('id');

            if ($categoryId) {
                $query->where('products.category_id', $categoryId);
            }
        }

        if ($brandSlug = $request->query('brand')) {
            $brandId = DB::table('brands')
                ->where('slug', $brandSlug)
                ->where('is_active', true)
                ->value('id');

            if ($brandId) {
                $query->where('products.brand_id', $brandId);
            }
        }

        if (is_numeric($request->query('price_min'))) {
            $query->where('products.price', '>=', (float) $request->query('price_min'));
        }

        if (is_numeric($request->query('price_max'))) {
            $query->where('products.price', '<=', (float) $request->query('price_max'));
        }

        // Apply sorting
        $sortBy = $request->query('sort', 'featured');
        switch ($sortBy) {
            case 'newest':
                $query->orderByDesc('products.created_at');
                break;
            case 'price_low':
                $query->orderBy('products.price');
                break;
            case 'price_high':
                $query->orderByDesc('products.price');
                break;
            case 'name_asc':
                $query->orderBy('products.name');
                break;
            case 'name_desc':
                $query->orderByDesc('products.name');
                break;
            case 'featured':
            default:
                $query->orderByDesc('products.is_featured')
                    ->orderByDesc('products.created_at');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Get primary images for products
        $productIds = $products->pluck('id')->toArray();
        $primaryImages = [];
        if (!empty($productIds)) {
            // Get primary images first
            $primaryImageRecords = DB::table('product_images')
                ->whereIn('product_id', $productIds)
                ->where('is_primary', true)
                ->select('product_id', 'file_path', 'alt_text')
                ->get()
                ->keyBy('product_id');
            
            // Get products without primary images
            $productsWithoutPrimary = array_diff($productIds, $primaryImageRecords->keys()->toArray());
            
            // If no primary image, get first image ordered by sort_order
            $fallbackImages = collect();
            if (!empty($productsWithoutPrimary)) {
                $fallbackImages = DB::table('product_images')
                    ->whereIn('product_id', $productsWithoutPrimary)
                    ->orderBy('sort_order')
                    ->select('product_id', 'file_path', 'alt_text')
                    ->get()
                    ->groupBy('product_id')
                    ->map(function ($group) {
                        return $group->first();
                    });
            }
            
            // Merge primary and fallback images
            $primaryImages = $primaryImageRecords->merge($fallbackImages);
        }

        // Attach images to products
        $products->getCollection()->transform(function ($product) use ($primaryImages) {
            $product->image = $primaryImages[$product->id] ?? null;
            if (!$product->image) {
                // Fallback placeholder image
                $product->image = (object) [
                    'file_path' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
                    'alt_text' => $product->name ?? 'Product image',
                ];
            }
            return $product;
        });

        // Get price range for filter display
        $priceRange = DB::table('products')
            ->where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        $categories = CacheService::getCategories()->map(function ($cat) {
            return (object) ['name' => $cat->name, 'slug' => $cat->slug];
        });

        $brands = CacheService::getBrands()->map(function ($brand) {
            return (object) ['name' => $brand->name, 'slug' => $brand->slug];
        });

        $fallbackProducts = collect();
        if ($products->isEmpty()) {
            $fallbackProducts = collect([
                [
                    'id' => 0,
                    'slug' => '#',
                    'name' => 'Concept Sneaker MKII',
                    'short_description' => 'Carbon-fiber heel counter and luminous midsole for night city runs.',
                    'price' => 189.00,
                    'is_featured' => true,
                    'category_name' => 'Footwear',
                    'brand_name' => 'PulseWave',
                    'image' => (object) [
                        'file_path' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
                        'alt_text' => 'Concept Sneaker MKII',
                    ],
                ],
                [
                    'id' => 0,
                    'slug' => '#',
                    'name' => 'Quantum Backpack',
                    'short_description' => 'Modular storage, RFID shielding, and hidden power compartment.',
                    'price' => 159.00,
                    'is_featured' => false,
                    'category_name' => 'Accessories',
                    'brand_name' => 'Lumen Studio',
                    'image' => (object) [
                        'file_path' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
                        'alt_text' => 'Quantum Backpack',
                    ],
                ],
                [
                    'id' => 0,
                    'slug' => '#',
                    'name' => 'Lattice Desk Lamp',
                    'short_description' => 'Adaptive color temperature with ambient motion detection.',
                    'price' => 129.00,
                    'is_featured' => false,
                    'category_name' => 'Workspace',
                    'brand_name' => 'Neon Forge',
                    'image' => (object) [
                        'file_path' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=800&q=80',
                        'alt_text' => 'Lattice Desk Lamp',
                    ],
                ],
            ])->map(fn ($item) => (object) $item);
        }

        $response = [
            'products' => $products,
            'fallbackProducts' => $fallbackProducts,
            'categories' => $categories,
            'brands' => $brands,
            'priceRange' => $priceRange,
            'filters' => [
                'search' => $request->query('search'),
                'featured' => $request->boolean('featured'),
                'category' => $request->query('category'),
                'brand' => $request->query('brand'),
                'price_min' => $request->query('price_min'),
                'price_max' => $request->query('price_max'),
                'sort' => $sortBy,
            ],
        ];

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'products' => $products->items()->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'slug' => $product->slug,
                        'name' => $product->name,
                        'short_description' => $product->short_description,
                        'price' => $product->price,
                        'is_featured' => $product->is_featured,
                        'category_name' => $product->category_name,
                        'brand_name' => $product->brand_name,
                        'image' => $product->image ?? null,
                    ];
                }),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                ],
                'filters' => $response['filters'],
            ]);
        }

        return view('frontwebsite.pages.products.index', $response);
    }

    public function show(string $slug)
    {
        $product = DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->select(
                'products.*',
                'categories.name as category_name',
                'categories.slug as category_slug',
                'brands.name as brand_name',
                'brands.slug as brand_slug'
            )
            ->where('products.slug', $slug)
            ->where('products.is_active', true)
            ->first();

        if (!$product) {
            abort(404);
        }

        $images = DB::table('product_images')
            ->where('product_id', $product->id)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order')
            ->get();

        if ($images->isEmpty()) {
            $images = collect([
                (object) [
                    'file_path' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
                    'alt_text' => $product->name,
                ],
            ]);
        }

        // Get reviews with helpful counts
        $reviews = DB::table('product_reviews')
            ->leftJoin('users', 'users.id', '=', 'product_reviews.user_id')
            ->leftJoin(DB::raw('(SELECT review_id, COUNT(*) FILTER (WHERE is_helpful = true) as helpful_count FROM review_helpfulness GROUP BY review_id) as helpful'), 'product_reviews.id', '=', 'helpful.review_id')
            ->select(
                'product_reviews.id',
                'product_reviews.rating',
                'product_reviews.title',
                'product_reviews.body',
                'product_reviews.created_at',
                'users.name as user_name',
                DB::raw('COALESCE(helpful.helpful_count, 0) as helpful_count')
            )
            ->where('product_reviews.product_id', $product->id)
            ->where('product_reviews.is_visible', true)
            ->orderByDesc('product_reviews.created_at')
            ->get();

        $averageRating = $reviews->avg('rating');

        // Get product questions and answers
        $questions = DB::table('product_questions')
            ->leftJoin('users', 'product_questions.user_id', '=', 'users.id')
            ->where('product_questions.product_id', $product->id)
            ->where('product_questions.is_visible', true)
            ->select('product_questions.*', 'users.name as user_name')
            ->orderByDesc('product_questions.created_at')
            ->get();

        // Get answers for each question
        foreach ($questions as $question) {
            $question->answers = DB::table('product_answers')
                ->leftJoin('admins', 'product_answers.admin_id', '=', 'admins.id')
                ->leftJoin('users', 'product_answers.user_id', '=', 'users.id')
                ->where('product_answers.question_id', $question->id)
                ->where('product_answers.is_visible', true)
                ->select('product_answers.*', 'admins.name as admin_name', 'users.name as user_name')
                ->orderBy('product_answers.created_at')
                ->get();
        }

        $related = DB::table('products')
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
            ->where('products.id', '!=', $product->id)
            ->orderByDesc('products.created_at')
            ->limit(4)
            ->get();

        return view('frontwebsite.pages.products.show', [
            'product' => $product,
            'images' => $images,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'questions' => $questions,
            'related' => $related,
        ]);
    }
}
