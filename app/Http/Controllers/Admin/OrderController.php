<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'orders.*',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderByDesc('orders.placed_at')
            ->orderByDesc('orders.created_at');

        // Filters
        if ($request->filled('status')) {
            $query->where('orders.status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('orders.payment_status', $request->payment_status);
        }
        if ($request->filled('fulfillment_status')) {
            $query->where('orders.fulfillment_status', $request->fulfillment_status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('orders.order_number', 'ILIKE', "%{$search}%")
                    ->orWhere('users.name', 'ILIKE', "%{$search}%")
                    ->orWhere('users.email', 'ILIKE', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show($orderNumber)
    {
        $order = DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'orders.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.phone as user_phone'
            )
            ->where('orders.order_number', $orderNumber)
            ->first();

        if (!$order) {
            abort(404);
        }

        $orderItems = DB::table('order_items')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.order_id', $order->id)
            ->select(
                'order_items.*',
                'products.slug as product_slug',
                'products.name as current_product_name'
            )
            ->get();

        $shippingAddress = DB::table('order_addresses')
            ->where('order_id', $order->id)
            ->where('type', 'shipping')
            ->first();

        $billingAddress = DB::table('order_addresses')
            ->where('order_id', $order->id)
            ->where('type', 'billing')
            ->first();

        $payment = DB::table('payments')
            ->where('order_id', $order->id)
            ->first();

        $shipments = DB::table('shipments')
            ->where('order_id', $order->id)
            ->orderByDesc('created_at')
            ->get();

        $refunds = DB::table('refunds')
            ->leftJoin('payments', 'refunds.payment_id', '=', 'payments.id')
            ->where('payments.order_id', $order->id)
            ->select('refunds.*')
            ->get();

        // Get order notes (we'll use a simple approach - storing in order notes field for now)
        // In a real system, you might have a separate order_notes table

        return view('admin.orders.show', [
            'order' => $order,
            'orderItems' => $orderItems,
            'shippingAddress' => $shippingAddress,
            'billingAddress' => $billingAddress,
            'payment' => $payment,
            'shipments' => $shipments,
            'refunds' => $refunds,
        ]);
    }

    public function updateStatus(Request $request, $orderNumber)
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'in:pending,processing,shipped,delivered,cancelled,refunded'],
            'payment_status' => ['nullable', 'string', 'in:pending,paid,failed,refunded'],
            'fulfillment_status' => ['nullable', 'string', 'in:unfulfilled,fulfilled,partially_fulfilled,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $order = DB::table('orders')
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        $updateData = ['updated_at' => now()];

        if (isset($validated['status'])) {
            $updateData['status'] = $validated['status'];
            if ($validated['status'] === 'cancelled' && !$order->cancelled_at) {
                $updateData['cancelled_at'] = now();
            }
        }

        if (isset($validated['payment_status'])) {
            $updateData['payment_status'] = $validated['payment_status'];
            if ($validated['payment_status'] === 'paid' && !$order->paid_at) {
                $updateData['paid_at'] = now();
            }
        }

        if (isset($validated['fulfillment_status'])) {
            $updateData['fulfillment_status'] = $validated['fulfillment_status'];
        }

        if (isset($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        DB::table('orders')
            ->where('id', $order->id)
            ->update($updateData);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Order updated successfully.',
        ]);
    }
}
