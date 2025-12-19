<?php

namespace App\Http\Controllers\FrontWebsite;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
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

        return view('frontwebsite.pages.cart.index', [
            'cart' => $cartWithItems,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $cart = $this->cartService->getOrCreateCart();
        $quantity = $validated['quantity'] ?? 1;

        $success = $this->cartService->addItem(
            $cart->id,
            $validated['product_id'],
            $quantity,
            $validated['variant_id'] ?? null
        );

        if (!$success) {
            return back()->with('error', 'Product not found or unavailable.');
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Item added to cart successfully.',
        ]);
    }

    public function update(Request $request, $itemId)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $success = $this->cartService->updateItem($itemId, $validated['quantity']);

        if (!$success) {
            return back()->with('error', 'Cart item not found.');
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Cart updated successfully.',
        ]);
    }

    public function destroy($itemId)
    {
        $success = $this->cartService->removeItem($itemId);

        if (!$success) {
            return back()->with('error', 'Cart item not found.');
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Item removed from cart.',
        ]);
    }

    public function miniCart()
    {
        $cart = $this->cartService->getOrCreateCart();
        $cartWithItems = $this->cartService->getCartWithItems($cart->id);
        $itemCount = $this->cartService->getItemCount($cart->id);

        return response()->json([
            'cart' => $cartWithItems,
            'item_count' => $itemCount,
        ]);
    }
}
