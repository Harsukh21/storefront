<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductOptionController extends Controller
{
    public function index(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $optionTypes = DB::table('product_option_types')
            ->where('product_id', $product)
            ->orderBy('name')
            ->get();

        // Get values for each option type
        foreach ($optionTypes as $optionType) {
            $optionType->values = DB::table('product_option_values')
                ->where('product_option_type_id', $optionType->id)
                ->orderBy('sort_order')
                ->orderBy('value')
                ->get();
        }

        return view('admin.catalog.products.options', [
            'product' => $productRecord,
            'optionTypes' => $optionTypes,
        ]);
    }

    public function storeType(Request $request, int $product)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        if (!$productRecord) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'display_name' => ['nullable', 'string', 'max:120'],
        ]);

        $optionTypeId = DB::table('product_option_types')->insertGetId([
            'product_id' => $product,
            'name' => $data['name'],
            'display_name' => $data['display_name'] ?? $data['name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Product option type created', ['option_type_id' => $optionTypeId, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Option type created successfully.']);

        return redirect()->route('admin.catalog.products.options', $product);
    }

    public function updateType(Request $request, int $product, int $optionType)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $optionTypeRecord = DB::table('product_option_types')->where('id', $optionType)->where('product_id', $product)->first();

        if (!$productRecord || !$optionTypeRecord) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'display_name' => ['nullable', 'string', 'max:120'],
        ]);

        DB::table('product_option_types')->where('id', $optionType)->update([
            'name' => $data['name'],
            'display_name' => $data['display_name'] ?? $data['name'],
            'updated_at' => now(),
        ]);

        Log::info('Product option type updated', ['option_type_id' => $optionType, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Option type updated successfully.']);

        return redirect()->route('admin.catalog.products.options', $product);
    }

    public function destroyType(int $product, int $optionType)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $optionTypeRecord = DB::table('product_option_types')->where('id', $optionType)->where('product_id', $product)->first();

        if (!$productRecord || !$optionTypeRecord) {
            abort(404);
        }

        // Delete option values first
        DB::table('product_option_values')->where('product_option_type_id', $optionType)->delete();
        
        // Delete option type
        DB::table('product_option_types')->where('id', $optionType)->delete();

        Log::warning('Product option type deleted', ['option_type_id' => $optionType, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Option type deleted successfully.']);

        return redirect()->route('admin.catalog.products.options', $product);
    }

    public function storeValue(Request $request, int $product, int $optionType)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $optionTypeRecord = DB::table('product_option_types')->where('id', $optionType)->where('product_id', $product)->first();

        if (!$productRecord || !$optionTypeRecord) {
            abort(404);
        }

        $data = $request->validate([
            'value' => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::table('product_option_values')->insert([
            'product_option_type_id' => $optionType,
            'value' => $data['value'],
            'sort_order' => $data['sort_order'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Product option value created', ['option_type_id' => $optionType, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Option value added successfully.']);

        return redirect()->route('admin.catalog.products.options', $product);
    }

    public function updateValue(Request $request, int $product, int $optionType, int $optionValue)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $optionTypeRecord = DB::table('product_option_types')->where('id', $optionType)->where('product_id', $product)->first();
        $optionValueRecord = DB::table('product_option_values')->where('id', $optionValue)->where('product_option_type_id', $optionType)->first();

        if (!$productRecord || !$optionTypeRecord || !$optionValueRecord) {
            abort(404);
        }

        $data = $request->validate([
            'value' => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::table('product_option_values')->where('id', $optionValue)->update([
            'value' => $data['value'],
            'sort_order' => $data['sort_order'] ?? 0,
            'updated_at' => now(),
        ]);

        Log::info('Product option value updated', ['option_value_id' => $optionValue, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Option value updated successfully.']);

        return redirect()->route('admin.catalog.products.options', $product);
    }

    public function destroyValue(int $product, int $optionType, int $optionValue)
    {
        $productRecord = DB::table('products')->where('id', $product)->first();
        $optionTypeRecord = DB::table('product_option_types')->where('id', $optionType)->where('product_id', $product)->first();
        $optionValueRecord = DB::table('product_option_values')->where('id', $optionValue)->where('product_option_type_id', $optionType)->first();

        if (!$productRecord || !$optionTypeRecord || !$optionValueRecord) {
            abort(404);
        }

        DB::table('product_option_values')->where('id', $optionValue)->delete();

        Log::warning('Product option value deleted', ['option_value_id' => $optionValue, 'product_id' => $product, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Option value deleted successfully.']);

        return redirect()->route('admin.catalog.products.options', $product);
    }
}


