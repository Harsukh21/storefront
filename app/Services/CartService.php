<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create cart for current user/session
     */
    public function getOrCreateCart()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $cart = DB::table('carts')
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if (!$cart) {
            $cartId = DB::table('carts')->insertGetId([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'currency' => 'USD',
                'subtotal' => 0,
                'discount_total' => 0,
                'tax_total' => 0,
                'shipping_total' => 0,
                'grand_total' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cart = DB::table('carts')->where('id', $cartId)->first();
        }

        return $cart;
    }

    /**
     * Add item to cart
     */
    public function addItem($cartId, $productId, $quantity = 1, $variantId = null)
    {
        $product = DB::table('products')->where('id', $productId)->where('is_active', true)->first();

        if (!$product) {
            return false;
        }

        // Check if item already exists in cart
        $existingItem = DB::table('cart_items')
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem->quantity + $quantity;
            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $newQuantity;

            DB::table('cart_items')
                ->where('id', $existingItem->id)
                ->update([
                    'quantity' => $newQuantity,
                    'total_price' => $totalPrice,
                    'updated_at' => now(),
                ]);
        } else {
            // Create new item
            $unitPrice = $product->price;
            $totalPrice = $unitPrice * $quantity;

            DB::table('cart_items')->insert([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->recalculateCart($cartId);
        return true;
    }

    /**
     * Update cart item quantity
     */
    public function updateItem($itemId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeItem($itemId);
        }

        $item = DB::table('cart_items')->where('id', $itemId)->first();
        if (!$item) {
            return false;
        }

        $unitPrice = $item->unit_price;
        $totalPrice = $unitPrice * $quantity;

        DB::table('cart_items')
            ->where('id', $itemId)
            ->update([
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'updated_at' => now(),
            ]);

        $this->recalculateCart($item->cart_id);
        return true;
    }

    /**
     * Remove item from cart
     */
    public function removeItem($itemId)
    {
        $item = DB::table('cart_items')->where('id', $itemId)->first();
        if (!$item) {
            return false;
        }

        $cartId = $item->cart_id;
        DB::table('cart_items')->where('id', $itemId)->delete();
        $this->recalculateCart($cartId);
        return true;
    }

    /**
     * Recalculate cart totals
     */
    public function recalculateCart($cartId)
    {
        $items = DB::table('cart_items')
            ->where('cart_id', $cartId)
            ->get();

        $subtotal = $items->sum('total_price');
        $discountTotal = 0; // Placeholder for future discount logic
        $taxTotal = 0; // Placeholder for future tax calculation
        $shippingTotal = 0; // Will be calculated during checkout
        $grandTotal = $subtotal + $taxTotal + $shippingTotal - $discountTotal;

        DB::table('carts')
            ->where('id', $cartId)
            ->update([
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'shipping_total' => $shippingTotal,
                'grand_total' => $grandTotal,
                'updated_at' => now(),
            ]);
    }

    /**
     * Get cart with items
     */
    public function getCartWithItems($cartId)
    {
        $cart = DB::table('carts')->where('id', $cartId)->first();
        if (!$cart) {
            return null;
        }

        $items = DB::table('cart_items')
            ->leftJoin('products', 'cart_items.product_id', '=', 'products.id')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->where('cart_items.cart_id', $cartId)
            ->select(
                'cart_items.*',
                'products.name as product_name',
                'products.slug as product_slug',
                'products.short_description as product_description',
                'brands.name as brand_name'
            )
            ->get();

        $cart->items = $items;
        return $cart;
    }

    /**
     * Merge session cart into user cart on login
     */
    public function mergeSessionCart($userId)
    {
        $sessionId = Session::getId();
        $sessionCart = DB::table('carts')
            ->where('session_id', $sessionId)
            ->whereNull('user_id')
            ->first();

        if (!$sessionCart) {
            return;
        }

        $userCart = DB::table('carts')
            ->where('user_id', $userId)
            ->first();

        if ($userCart) {
            // Merge items from session cart into user cart
            $sessionItems = DB::table('cart_items')->where('cart_id', $sessionCart->id)->get();
            foreach ($sessionItems as $item) {
                $this->addItem($userCart->id, $item->product_id, $item->quantity, $item->product_variant_id);
            }
            // Delete session cart
            DB::table('cart_items')->where('cart_id', $sessionCart->id)->delete();
            DB::table('carts')->where('id', $sessionCart->id)->delete();
        } else {
            // Assign session cart to user
            DB::table('carts')
                ->where('id', $sessionCart->id)
                ->update([
                    'user_id' => $userId,
                    'session_id' => null,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Get cart item count
     */
    public function getItemCount($cartId = null)
    {
        if (!$cartId) {
            $cart = $this->getOrCreateCart();
            $cartId = $cart->id;
        }

        return DB::table('cart_items')
            ->where('cart_id', $cartId)
            ->sum('quantity');
    }
}
