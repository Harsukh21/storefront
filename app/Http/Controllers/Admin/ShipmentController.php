<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentController extends Controller
{
    public function store(Request $request, $orderNumber)
    {
        $order = DB::table('orders')
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        $validated = $request->validate([
            'carrier' => ['required', 'string', 'max:60'],
            'service' => ['nullable', 'string', 'max:60'],
            'tracking_number' => ['required', 'string', 'max:120'],
            'items' => ['required', 'array'],
            'items.*.order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Generate shipment number
        $shipmentNumber = 'SHIP-' . strtoupper(uniqid());

        // Create shipment
        $shipmentId = DB::table('shipments')->insertGetId([
            'order_id' => $order->id,
            'shipment_number' => $shipmentNumber,
            'carrier' => $validated['carrier'],
            'service' => $validated['service'] ?? null,
            'tracking_number' => $validated['tracking_number'],
            'status' => 'pending',
            'shipped_at' => null,
            'delivered_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create shipment items
        foreach ($validated['items'] as $item) {
            DB::table('shipment_items')->insert([
                'shipment_id' => $shipmentId,
                'order_item_id' => $item['order_item_id'],
                'quantity' => $item['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update order fulfillment status
        $totalOrderItems = DB::table('order_items')
            ->where('order_id', $order->id)
            ->sum('quantity');

        $totalShippedItems = DB::table('shipment_items')
            ->leftJoin('shipments', 'shipment_items.shipment_id', '=', 'shipments.id')
            ->where('shipments.order_id', $order->id)
            ->sum('shipment_items.quantity');

        $fulfillmentStatus = 'unfulfilled';
        if ($totalShippedItems >= $totalOrderItems) {
            $fulfillmentStatus = 'fulfilled';
        } elseif ($totalShippedItems > 0) {
            $fulfillmentStatus = 'partially_fulfilled';
        }

        DB::table('orders')
            ->where('id', $order->id)
            ->update([
                'fulfillment_status' => $fulfillmentStatus,
                'updated_at' => now(),
            ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Shipment created successfully.',
        ]);
    }

    public function update(Request $request, $shipmentId)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,shipped,in_transit,delivered,returned'],
            'tracking_number' => ['nullable', 'string', 'max:120'],
            'carrier' => ['nullable', 'string', 'max:60'],
            'service' => ['nullable', 'string', 'max:60'],
        ]);

        $shipment = DB::table('shipments')->where('id', $shipmentId)->first();

        if (!$shipment) {
            return back()->with('error', 'Shipment not found.');
        }

        $updateData = [
            'status' => $validated['status'],
            'updated_at' => now(),
        ];

        if ($validated['status'] === 'shipped' && !$shipment->shipped_at) {
            $updateData['shipped_at'] = now();
        }

        if ($validated['status'] === 'delivered' && !$shipment->delivered_at) {
            $updateData['delivered_at'] = now();
        }

        if (isset($validated['tracking_number'])) {
            $updateData['tracking_number'] = $validated['tracking_number'];
        }

        if (isset($validated['carrier'])) {
            $updateData['carrier'] = $validated['carrier'];
        }

        if (isset($validated['service'])) {
            $updateData['service'] = $validated['service'];
        }

        DB::table('shipments')
            ->where('id', $shipmentId)
            ->update($updateData);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Shipment updated successfully.',
        ]);
    }
}
