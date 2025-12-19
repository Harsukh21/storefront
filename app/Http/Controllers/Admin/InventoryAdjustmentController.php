<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('inventory_adjustments')
            ->leftJoin('inventory_items', 'inventory_adjustments.inventory_item_id', '=', 'inventory_items.id')
            ->leftJoin('products', 'inventory_items.product_id', '=', 'products.id')
            ->leftJoin('product_variants', 'inventory_items.product_variant_id', '=', 'product_variants.id')
            ->leftJoin('admins', 'inventory_adjustments.admin_id', '=', 'admins.id')
            ->select(
                'inventory_adjustments.*',
                'products.name as product_name',
                'products.sku as product_sku',
                'product_variants.name as variant_name',
                'product_variants.sku as variant_sku',
                'admins.name as admin_name'
            )
            ->orderByDesc('inventory_adjustments.created_at');

        if ($request->filled('product_id')) {
            $query->where('inventory_items.product_id', $request->product_id);
        }

        if ($request->filled('adjustment_type')) {
            $query->where('inventory_adjustments.adjustment_type', $request->adjustment_type);
        }

        $adjustments = $query->paginate(20)->withQueryString();

        return view('admin.inventory.adjustments', [
            'adjustments' => $adjustments,
            'filters' => [
                'product_id' => $request->query('product_id'),
                'adjustment_type' => $request->query('adjustment_type'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_item_id' => ['required', 'integer', 'exists:inventory_items,id'],
            'adjustment_type' => ['required', 'string', 'in:add,remove,set,damaged,returned'],
            'quantity_change' => ['required', 'integer'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $inventoryItem = DB::table('inventory_items')->where('id', $data['inventory_item_id'])->first();
        if (!$inventoryItem) {
            abort(404);
        }

        // Calculate new quantity based on adjustment type
        $newQuantity = $inventoryItem->quantity_on_hand;
        switch ($data['adjustment_type']) {
            case 'add':
                $newQuantity += $data['quantity_change'];
                break;
            case 'remove':
                $newQuantity -= $data['quantity_change'];
                break;
            case 'set':
                $newQuantity = $data['quantity_change'];
                break;
            case 'damaged':
            case 'returned':
                $newQuantity += $data['quantity_change'];
                break;
        }

        if ($newQuantity < 0) {
            return back()->withErrors(['quantity_change' => 'Adjustment would result in negative inventory'])->withInput();
        }

        // Update inventory item
        DB::table('inventory_items')->where('id', $data['inventory_item_id'])->update([
            'quantity_on_hand' => $newQuantity,
            'quantity_available' => max(0, $newQuantity - $inventoryItem->quantity_reserved),
            'updated_at' => now(),
        ]);

        // Create adjustment record
        $adjustmentId = DB::table('inventory_adjustments')->insertGetId([
            'inventory_item_id' => $data['inventory_item_id'],
            'admin_id' => auth('admin')->id(),
            'adjustment_type' => $data['adjustment_type'],
            'quantity_change' => $data['quantity_change'],
            'note' => $data['note'] ?? null,
            'created_at' => now(),
        ]);

        Log::info('Inventory adjustment created', [
            'adjustment_id' => $adjustmentId,
            'inventory_item_id' => $data['inventory_item_id'],
            'type' => $data['adjustment_type'],
            'change' => $data['quantity_change'],
            'admin_id' => auth('admin')->id(),
        ]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Inventory adjustment recorded successfully.']);

        return redirect()->route('admin.inventory.adjustments.index');
    }
}


