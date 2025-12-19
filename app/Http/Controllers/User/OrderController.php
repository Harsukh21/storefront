<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = DB::table('orders')
            ->where('user_id', Auth::id())
            ->orderByDesc('placed_at')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('user.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show($orderNumber)
    {
        $order = DB::table('orders')
            ->where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
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
            ->get();

        return view('user.orders.show', [
            'order' => $order,
            'orderItems' => $orderItems,
            'shippingAddress' => $shippingAddress,
            'billingAddress' => $billingAddress,
            'payment' => $payment,
            'shipments' => $shipments,
        ]);
    }
}
