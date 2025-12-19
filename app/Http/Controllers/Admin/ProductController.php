<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('products')
            ->select('products.id', 'products.name', 'products.slug', 'products.price', 'products.is_active', 'products.is_featured', 'categories.name as category_name', 'brands.name as brand_name')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->orderByDesc('products.created_at');

        if ($search = $request->query('search')) {
            $query->where(function ($inner) use ($search) {
                $inner->where('products.name', 'ILIKE', "%{$search}%")
                    ->orWhere('products.sku', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('products.is_active', $request->status === 'active');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('admin.catalog.products.index', [
            'products' => $products,
            'filters' => [
                'search' => $request->query('search'),
                'status' => $request->query('status'),
            ],
        ]);
    }

    public function create()
    {
        $categories = DB::table('categories')->select('id', 'name')->orderBy('name')->get();
        $brands = DB::table('brands')->select('id', 'name')->orderBy('name')->get();

        return view('admin.catalog.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        $productId = DB::table('products')->insertGetId([
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'name' => $data['name'],
            'sku' => $data['sku'],
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'tax_class' => null,
            'weight' => $data['weight'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'depth' => $data['depth'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'meta_title' => null,
            'meta_description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CacheService::clearProductCache($productId);
        Log::info('Product created', ['product_id' => $productId, 'name' => $data['name'], 'sku' => $data['sku'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Product created successfully.']);

        return redirect()->route('admin.catalog.products.index');
    }

    public function edit(int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $categories = DB::table('categories')->select('id', 'name')->orderBy('name')->get();
        $brands = DB::table('brands')->select('id', 'name')->orderBy('name')->get();

        return view('admin.catalog.products.edit', [
            'product' => $productRecord,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function update(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $data = $this->validateProduct($request, $product);

        DB::table('products')->where('id', $product)->update([
            'category_id' => $data['category_id'],
            'brand_id' => $data['brand_id'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'name' => $data['name'],
            'sku' => $data['sku'],
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'weight' => $data['weight'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'depth' => $data['depth'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'updated_at' => now(),
        ]);

        CacheService::clearProductCache($product);
        Log::info('Product updated', ['product_id' => $product, 'name' => $data['name'], 'sku' => $data['sku'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Product updated successfully.']);

        return redirect()->route('admin.catalog.products.index');
    }

    public function destroy(int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        DB::table('products')->where('id', $product)->delete();

        CacheService::clearProductCache($product);
        Log::warning('Product deleted', ['product_id' => $product, 'name' => $productRecord->name ?? 'Unknown', 'sku' => $productRecord->sku ?? 'Unknown', 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Product deleted.']);

        return redirect()->route('admin.catalog.products.index');
    }

    protected function validateProduct(Request $request, ?int $productId = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191'],
            'sku' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', 'integer'],
            'brand_id' => ['nullable', 'integer'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'depth' => ['nullable', 'numeric', 'min:0'],
        ];

        $data = $request->validate($rules);

        if (!DB::table('categories')->where('id', $data['category_id'])->exists()) {
            Validator::make([], [])->after(function ($validator) {
                $validator->errors()->add('category_id', 'Selected category does not exist.');
            })->validate();
        }

        if (!empty($data['brand_id']) && !DB::table('brands')->where('id', $data['brand_id'])->exists()) {
            Validator::make([], [])->after(function ($validator) {
                $validator->errors()->add('brand_id', 'Selected brand does not exist.');
            })->validate();
        }

        $slug = $data['slug'] ?: Str::slug($data['name']);
        $slugQuery = DB::table('products')->where('slug', $slug);
        if ($productId) {
            $slugQuery->where('id', '!=', $productId);
        }
        if ($slugQuery->exists()) {
            Validator::make([], [])->after(function ($validator) {
                $validator->errors()->add('slug', 'The slug has already been taken.');
            })->validate();
        }

        $data['slug'] = $slug;
        $data['sku'] = $data['sku'] ?? Str::upper(Str::random(8));

        return $data;
    }

    public function images(int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $images = DB::table('product_images')
            ->where('product_id', $product)
            ->orderByDesc('is_primary')
            ->orderBy('sort_order')
            ->get();

        return view('admin.catalog.products.images', [
            'product' => $productRecord,
            'images' => $images,
        ]);
    }

    public function storeImage(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $request->validate([
            'file_path' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:191'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        // If setting as primary, unset other primary images
        if ($request->boolean('is_primary')) {
            DB::table('product_images')
                ->where('product_id', $product)
                ->update(['is_primary' => false]);
        }

        // Get max sort_order
        $maxSortOrder = DB::table('product_images')
            ->where('product_id', $product)
            ->max('sort_order') ?? 0;

        $imageId = DB::table('product_images')->insertGetId([
            'product_id' => $product,
            'file_path' => $request->file_path,
            'alt_text' => $request->alt_text,
            'is_primary' => $request->boolean('is_primary', false),
            'sort_order' => $maxSortOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CacheService::clearProductCache($product);
        Log::info('Product image added', ['product_id' => $product, 'image_id' => $imageId, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Image added successfully.']);

        return redirect()->route('admin.catalog.products.images', $product);
    }

    public function updateImage(Request $request, int $product, int $image)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $imageRecord = DB::table('product_images')->where('id', $image)->where('product_id', $product)->first();

        if (!$productRecord || !$imageRecord) {
            abort(404);
        }

        $request->validate([
            'file_path' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:191'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        $updateData = [];
        if ($request->has('file_path')) {
            $updateData['file_path'] = $request->file_path;
        }
        if ($request->has('alt_text')) {
            $updateData['alt_text'] = $request->alt_text;
        }
        if ($request->has('is_primary')) {
            // If setting as primary, unset other primary images
            if ($request->boolean('is_primary')) {
                DB::table('product_images')
                    ->where('product_id', $product)
                    ->where('id', '!=', $image)
                    ->update(['is_primary' => false]);
            }
            $updateData['is_primary'] = $request->boolean('is_primary');
        }

        $updateData['updated_at'] = now();

        DB::table('product_images')->where('id', $image)->update($updateData);

        CacheService::clearProductCache($product);
        Log::info('Product image updated', ['product_id' => $product, 'image_id' => $image, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Image updated successfully.']);

        return redirect()->route('admin.catalog.products.images', $product);
    }

    public function destroyImage(int $product, int $image)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $imageRecord = DB::table('product_images')->where('id', $image)->where('product_id', $product)->first();

        if (!$productRecord || !$imageRecord) {
            abort(404);
        }

        DB::table('product_images')->where('id', $image)->delete();

        CacheService::clearProductCache($product);
        Log::warning('Product image deleted', ['product_id' => $product, 'image_id' => $image, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Image deleted successfully.']);

        return redirect()->route('admin.catalog.products.images', $product);
    }

    public function setPrimaryImage(int $product, int $image)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $imageRecord = DB::table('product_images')->where('id', $image)->where('product_id', $product)->first();

        if (!$productRecord || !$imageRecord) {
            abort(404);
        }

        // Unset all primary images
        DB::table('product_images')
            ->where('product_id', $product)
            ->update(['is_primary' => false]);

        // Set this image as primary
        DB::table('product_images')
            ->where('id', $image)
            ->update(['is_primary' => true, 'updated_at' => now()]);

        CacheService::clearProductCache($product);
        Log::info('Product primary image set', ['product_id' => $product, 'image_id' => $image, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Primary image updated.']);

        return redirect()->route('admin.catalog.products.images', $product);
    }

    public function reorderImages(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer'],
        ]);

        foreach ($request->order as $sortOrder => $imageId) {
            DB::table('product_images')
                ->where('id', $imageId)
                ->where('product_id', $product)
                ->update(['sort_order' => $sortOrder + 1, 'updated_at' => now()]);
        }

        CacheService::clearProductCache($product);
        Log::info('Product images reordered', ['product_id' => $product, 'admin_id' => auth('admin')->id()]);

        return response()->json(['success' => true]);
    }
}
