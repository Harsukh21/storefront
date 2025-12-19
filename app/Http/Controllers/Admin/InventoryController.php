<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = DB::table('inventory_items')
            ->select(
                'inventory_items.id',
                'inventory_items.product_id',
                'inventory_items.quantity_on_hand',
                'inventory_items.quantity_reserved',
                'inventory_items.quantity_available',
                'products.name as product_name',
                'products.sku'
            )
            ->leftJoin('products', 'products.id', '=', 'inventory_items.product_id')
            ->orderBy('products.name')
            ->paginate(15);

        $products = DB::table('products')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.inventory.index', compact('inventory', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity_change' => ['required', 'integer', 'not_in:0'],
            'note' => ['nullable', 'string'],
        ]);

        $product = DB::table('products')->where('id', $data['product_id'])->first();

        if (!$product) {
            return back()->withErrors(['product_id' => 'Product not found.'])->withInput();
        }

        $inventoryItem = DB::table('inventory_items')->where('product_id', $product->id)->first();

        if ($inventoryItem) {
            $newOnHand = $inventoryItem->quantity_on_hand + $data['quantity_change'];
            $newReserved = $inventoryItem->quantity_reserved;
            $newAvailable = $newOnHand - $newReserved;

            DB::table('inventory_items')->where('id', $inventoryItem->id)->update([
                'quantity_on_hand' => $newOnHand,
                'quantity_available' => $newAvailable,
                'updated_at' => now(),
            ]);

            $inventoryItemId = $inventoryItem->id;
        } else {
            $newOnHand = max(0, $data['quantity_change']);
            DB::table('inventory_items')->insert([
                'product_id' => $product->id,
                'quantity_on_hand' => $newOnHand,
                'quantity_reserved' => 0,
                'quantity_available' => $newOnHand,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $inventoryItemId = DB::table('inventory_items')->where('product_id', $product->id)->value('id');
        }

        DB::table('inventory_adjustments')->insert([
            'inventory_item_id' => $inventoryItemId,
            'admin_id' => optional(Auth::guard('admin')->user())->id,
            'adjustment_type' => $data['quantity_change'] > 0 ? 'increase' : 'decrease',
            'quantity_change' => $data['quantity_change'],
            'note' => $data['note'],
            'created_at' => now(),
        ]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Inventory updated successfully.']);

        return redirect()->route('admin.inventory.index');
    }
}
