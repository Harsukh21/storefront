<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductAttributeController extends Controller
{
    public function index(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $attributes = DB::table('product_attributes')
            ->where('product_id', $product)
            ->orderBy('attribute_name')
            ->get();

        return view('admin.catalog.products.attributes', [
            'product' => $productRecord,
            'attributes' => $attributes,
        ]);
    }

    public function store(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $data = $request->validate([
            'attribute_name' => ['required', 'string', 'max:120'],
            'attribute_value' => ['required', 'string', 'max:255'],
        ]);

        $attributeId = DB::table('product_attributes')->insertGetId([
            'product_id' => $product,
            'attribute_name' => $data['attribute_name'],
            'attribute_value' => $data['attribute_value'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Product attribute created', ['attribute_id' => $attributeId, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Attribute added successfully.']);

        return redirect()->route('admin.catalog.products.attributes', $product);
    }

    public function update(Request $request, int $product, int $attribute)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $attributeRecord = DB::table('product_attributes')->where('id', $attribute)->where('product_id', $product)->first();

        if (!$productRecord || !$attributeRecord) {
            abort(404);
        }

        $data = $request->validate([
            'attribute_name' => ['required', 'string', 'max:120'],
            'attribute_value' => ['required', 'string', 'max:255'],
        ]);

        DB::table('product_attributes')->where('id', $attribute)->update([
            'attribute_name' => $data['attribute_name'],
            'attribute_value' => $data['attribute_value'],
            'updated_at' => now(),
        ]);

        Log::info('Product attribute updated', ['attribute_id' => $attribute, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Attribute updated successfully.']);

        return redirect()->route('admin.catalog.products.attributes', $product);
    }

    public function destroy(int $product, int $attribute)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $attributeRecord = DB::table('product_attributes')->where('id', $attribute)->where('product_id', $product)->first();

        if (!$productRecord || !$attributeRecord) {
            abort(404);
        }

        DB::table('product_attributes')->where('id', $attribute)->delete();

        Log::warning('Product attribute deleted', ['attribute_id' => $attribute, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Attribute deleted successfully.']);

        return redirect()->route('admin.catalog.products.attributes', $product);
    }
}


