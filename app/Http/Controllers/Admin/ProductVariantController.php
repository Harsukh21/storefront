<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    public function index(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $variants = DB::table('product_variants')
            ->where('product_id', $product)
            ->orderBy('created_at')
            ->get();

        return view('admin.catalog.products.variants', [
            'product' => $productRecord,
            'variants' => $variants,
        ]);
    }

    public function store(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:191'],
            'sku' => ['required', 'string', 'max:120', 'unique:product_variants,sku'],
            'barcode' => ['nullable', 'string', 'max:120'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'depth' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $variantId = DB::table('product_variants')->insertGetId([
            'product_id' => $product,
            'name' => $data['name'] ?? null,
            'sku' => $data['sku'],
            'barcode' => $data['barcode'] ?? null,
            'price' => $data['price'] ?? null,
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'weight' => $data['weight'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'depth' => $data['depth'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Product variant created', ['variant_id' => $variantId, 'product_id' => $product, 'sku' => $data['sku'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Variant created successfully.']);

        return redirect()->route('admin.catalog.products.variants', $product);
    }

    public function update(Request $request, int $product, int $variant)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $variantRecord = DB::table('product_variants')->where('id', $variant)->where('product_id', $product)->first();

        if (!$productRecord || !$variantRecord) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:191'],
            'sku' => ['required', 'string', 'max:120', 'unique:product_variants,sku,' . $variant],
            'barcode' => ['nullable', 'string', 'max:120'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'depth' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::table('product_variants')->where('id', $variant)->update([
            'name' => $data['name'] ?? null,
            'sku' => $data['sku'],
            'barcode' => $data['barcode'] ?? null,
            'price' => $data['price'] ?? null,
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'weight' => $data['weight'] ?? null,
            'width' => $data['width'] ?? null,
            'height' => $data['height'] ?? null,
            'depth' => $data['depth'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'updated_at' => now(),
        ]);

        Log::info('Product variant updated', ['variant_id' => $variant, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Variant updated successfully.']);

        return redirect()->route('admin.catalog.products.variants', $product);
    }

    public function destroy(int $product, int $variant)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $variantRecord = DB::table('product_variants')->where('id', $variant)->where('product_id', $product)->first();

        if (!$productRecord || !$variantRecord) {
            abort(404);
        }

        DB::table('product_variants')->where('id', $variant)->delete();

        Log::warning('Product variant deleted', ['variant_id' => $variant, 'product_id' => $product, 'sku' => $variantRecord->sku, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Variant deleted successfully.']);

        return redirect()->route('admin.catalog.products.variants', $product);
    }
}


