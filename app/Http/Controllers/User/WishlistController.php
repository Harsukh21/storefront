<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlist = $this->firstOrCreateDefaultWishlist($request->user()->id);

        $items = DB::table('wishlist_items')
            ->leftJoin('products', 'products.id', '=', 'wishlist_items.product_id')
            ->select(
                'wishlist_items.id',
                'products.name',
                'products.slug',
                'products.price',
                'products.short_description',
                'products.is_active',
                'wishlist_items.created_at'
            )
            ->where('wishlist_items.wishlist_id', $wishlist->id)
            ->orderByDesc('wishlist_items.created_at')
            ->get();

        return view('user.wishlist.index', [
            'wishlist' => $wishlist,
            'items' => $items,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_slug' => ['required', 'string'],
        ]);

        $product = DB::table('products')
            ->where('slug', $data['product_slug'])
            ->where('is_active', true)
            ->first();

        if (!$product) {
            session()->flash('toast', ['type' => 'danger', 'message' => 'Product not found or inactive.']);
            return back();
        }

        $wishlist = $this->firstOrCreateDefaultWishlist($request->user()->id);

        $exists = DB::table('wishlist_items')
            ->where('wishlist_id', $wishlist->id)
            ->where('product_id', $product->id)
            ->exists();

        if ($exists) {
            session()->flash('toast', ['type' => 'info', 'message' => 'Product is already in your wishlist.']);
            return back();
        }

        DB::table('wishlist_items')->insert([
            'wishlist_id' => $wishlist->id,
            'product_id' => $product->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Added to your wishlist.']);

        return back();
    }

    public function destroy(Request $request, int $item)
    {
        DB::table('wishlist_items')
            ->where('id', $item)
            ->whereIn('wishlist_id', function ($query) use ($request) {
                $query->select('id')
                    ->from('wishlists')
                    ->where('user_id', $request->user()->id);
            })
            ->delete();

        session()->flash('toast', ['type' => 'success', 'message' => 'Wishlist item removed.']);

        return back();
    }

    protected function firstOrCreateDefaultWishlist(int $userId): object
    {
        $wishlist = DB::table('wishlists')
            ->where('user_id', $userId)
            ->where('is_default', true)
            ->first();

        if ($wishlist) {
            return $wishlist;
        }

        $id = DB::table('wishlists')->insertGetId([
            'user_id' => $userId,
            'name' => 'Favorites',
            'is_default' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('wishlists')->where('id', $id)->first();
    }
}
