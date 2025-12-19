<?php

namespace App\Http\Controllers\FrontWebsite;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart();
        $cartWithItems = $this->cartService->getCartWithItems($cart->id);

        if (!$cartWithItems || $cartWithItems->items->isEmpty()) {
            return redirect()->route('front.cart')->with('toast', [
                'type' => 'warning',
                'message' => 'Your cart is empty.',
            ]);
        }

        $addresses = [];
        if (Auth::check()) {
            $addresses = DB::table('user_addresses')
                ->where('user_id', Auth::id())
                ->orderByDesc('is_default_shipping')
                ->orderByDesc('is_default_billing')
                ->orderByDesc('created_at')
                ->get();
        }

        return view('frontwebsite.pages.checkout.index', [
            'cart' => $cartWithItems,
            'addresses' => $addresses,
        ]);
    }

    public function store(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart();
        $cartWithItems = $this->cartService->getCartWithItems($cart->id);

        if (!$cartWithItems || $cartWithItems->items->isEmpty()) {
            return redirect()->route('front.cart')->with('toast', [
                'type' => 'warning',
                'message' => 'Your cart is empty.',
            ]);
        }

        $validated = $request->validate([
            'shipping_name' => ['required', 'string', 'max:150'],
            'shipping_phone' => ['nullable', 'string', 'max:40'],
            'shipping_line1' => ['required', 'string', 'max:255'],
            'shipping_line2' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:120'],
            'shipping_state' => ['nullable', 'string', 'max:120'],
            'shipping_postal_code' => ['nullable', 'string', 'max:30'],
            'shipping_country' => ['required', 'string', 'size:2'],
            'billing_same_as_shipping' => ['nullable', 'boolean'],
            'billing_name' => ['required_without:billing_same_as_shipping', 'string', 'max:150'],
            'billing_phone' => ['nullable', 'string', 'max:40'],
            'billing_line1' => ['required_without:billing_same_as_shipping', 'string', 'max:255'],
            'billing_line2' => ['nullable', 'string', 'max:255'],
            'billing_city' => ['required_without:billing_same_as_shipping', 'string', 'max:120'],
            'billing_state' => ['nullable', 'string', 'max:120'],
            'billing_postal_code' => ['nullable', 'string', 'max:30'],
            'billing_country' => ['required_without:billing_same_as_shipping', 'string', 'size:2'],
            'payment_method' => ['required', 'string', 'in:card,paypal,stripe'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Generate order number
        $orderNumber = 'ORD-' . strtoupper(uniqid());

        // Create order
        $orderId = DB::table('orders')->insertGetId([
            'order_number' => $orderNumber,
            'user_id' => Auth::id(),
            'cart_id' => $cart->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'fulfillment_status' => 'unfulfilled',
            'currency' => $cart->currency,
            'subtotal' => $cart->subtotal,
            'discount_total' => $cart->discount_total,
            'tax_total' => $cart->tax_total,
            'shipping_total' => $cart->shipping_total,
            'grand_total' => $cart->grand_total,
            'notes' => $validated['notes'] ?? null,
            'placed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create shipping address
        $shippingAddressId = DB::table('order_addresses')->insertGetId([
            'order_id' => $orderId,
            'type' => 'shipping',
            'recipient_name' => $validated['shipping_name'],
            'phone' => $validated['shipping_phone'] ?? null,
            'line1' => $validated['shipping_line1'],
            'line2' => $validated['shipping_line2'] ?? null,
            'city' => $validated['shipping_city'],
            'state' => $validated['shipping_state'] ?? null,
            'postal_code' => $validated['shipping_postal_code'] ?? null,
            'country' => $validated['shipping_country'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create billing address
        if ($request->boolean('billing_same_as_shipping')) {
            $billingAddressId = DB::table('order_addresses')->insertGetId([
                'order_id' => $orderId,
                'type' => 'billing',
                'recipient_name' => $validated['shipping_name'],
                'phone' => $validated['shipping_phone'] ?? null,
                'line1' => $validated['shipping_line1'],
                'line2' => $validated['shipping_line2'] ?? null,
                'city' => $validated['shipping_city'],
                'state' => $validated['shipping_state'] ?? null,
                'postal_code' => $validated['shipping_postal_code'] ?? null,
                'country' => $validated['shipping_country'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $billingAddressId = DB::table('order_addresses')->insertGetId([
                'order_id' => $orderId,
                'type' => 'billing',
                'recipient_name' => $validated['billing_name'],
                'phone' => $validated['billing_phone'] ?? null,
                'line1' => $validated['billing_line1'],
                'line2' => $validated['billing_line2'] ?? null,
                'city' => $validated['billing_city'],
                'state' => $validated['billing_state'] ?? null,
                'postal_code' => $validated['billing_postal_code'] ?? null,
                'country' => $validated['billing_country'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update order with address IDs
        DB::table('orders')
            ->where('id', $orderId)
            ->update([
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $billingAddressId,
                'updated_at' => now(),
            ]);

        // Create order items
        foreach ($cartWithItems->items as $cartItem) {
            $product = DB::table('products')->where('id', $cartItem->product_id)->first();
            
            DB::table('order_items')->insert([
                'order_id' => $orderId,
                'product_id' => $cartItem->product_id,
                'product_variant_id' => $cartItem->product_variant_id,
                'name_snapshot' => $product->name ?? $cartItem->product_name,
                'sku_snapshot' => $product->sku ?? null,
                'unit_price' => $cartItem->unit_price,
                'quantity' => $cartItem->quantity,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_price' => $cartItem->total_price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create payment intent stub
        $paymentId = DB::table('payments')->insertGetId([
            'order_id' => $orderId,
            'provider' => $validated['payment_method'],
            'transaction_id' => null,
            'amount' => $cart->grand_total,
            'currency' => $cart->currency,
            'status' => 'pending',
            'processed_at' => null,
            'raw_response' => json_encode(['stub' => true, 'method' => $validated['payment_method']]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Clear cart
        DB::table('cart_items')->where('cart_id', $cart->id)->delete();
        DB::table('carts')->where('id', $cart->id)->delete();

        return redirect()->route('front.checkout.confirmation', $orderNumber)->with('toast', [
            'type' => 'success',
            'message' => 'Order placed successfully!',
        ]);
    }

    public function confirmation($orderNumber)
    {
        $order = DB::table('orders')
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order) {
            abort(404);
        }

        // Verify ownership if user is logged in
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $orderItems = DB::table('order_items')
            ->where('order_id', $order->id)
            ->get();

        $shippingAddress = DB::table('order_addresses')
            ->where('order_id', $order->id)
            ->where('type', 'shipping')
            ->first();

        $billingAddress = DB::table('order_addresses')
            ->where('order_id', $order->id)
            ->where('type', 'billing')
            ->first();

        return view('frontwebsite.pages.checkout.confirmation', [
            'order' => $order,
            'orderItems' => $orderItems,
            'shippingAddress' => $shippingAddress,
            'billingAddress' => $billingAddress,
        ]);
    }
}
