<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $metrics = [
            'products' => DB::table('products')->count(),
            'categories' => DB::table('categories')->count(),
            'brands' => DB::table('brands')->count(),
            'lowStock' => DB::table('inventory_items')->where('quantity_available', '<', 5)->count(),
        ];

        $recentProducts = DB::table('products')
            ->select('id', 'name', 'slug', 'is_active', 'created_at')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $recentAdjustments = DB::table('inventory_adjustments')
            ->select('inventory_adjustments.quantity_change', 'inventory_adjustments.adjustment_type', 'inventory_adjustments.created_at', 'products.name as product_name')
            ->leftJoin('inventory_items', 'inventory_items.id', '=', 'inventory_adjustments.inventory_item_id')
            ->leftJoin('products', 'products.id', '=', 'inventory_items.product_id')
            ->latest('inventory_adjustments.created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', [
            'metrics' => $metrics,
            'recentProducts' => $recentProducts,
            'recentAdjustments' => $recentAdjustments,
        ]);
    }
}
