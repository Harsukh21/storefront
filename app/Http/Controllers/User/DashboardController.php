<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $userId = Auth::id();

        // Order statistics
        $totalOrders = DB::table('orders')
            ->where('user_id', $userId)
            ->count();

        $totalSpent = DB::table('orders')
            ->where('user_id', $userId)
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $pendingOrders = DB::table('orders')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        // Recent orders
        $recentOrders = DB::table('orders')
            ->where('user_id', $userId)
            ->orderByDesc('placed_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Wishlist count
        $wishlistCount = DB::table('wishlist_items')
            ->leftJoin('wishlists', 'wishlist_items.wishlist_id', '=', 'wishlists.id')
            ->where('wishlists.user_id', $userId)
            ->count();

        // Address count
        $addressCount = DB::table('user_addresses')
            ->where('user_id', $userId)
            ->count();

        return view('user.dashboard', [
            'totalOrders' => $totalOrders,
            'totalSpent' => $totalSpent ?? 0,
            'pendingOrders' => $pendingOrders,
            'recentOrders' => $recentOrders,
            'wishlistCount' => $wishlistCount,
            'addressCount' => $addressCount,
        ]);
    }
}
