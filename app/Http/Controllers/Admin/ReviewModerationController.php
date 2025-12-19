<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('product_reviews')
            ->leftJoin('products', 'product_reviews.product_id', '=', 'products.id')
            ->leftJoin('users', 'product_reviews.user_id', '=', 'users.id')
            ->select(
                'product_reviews.*',
                'products.name as product_name',
                'products.slug as product_slug',
                'users.name as user_name'
            )
            ->orderByDesc('product_reviews.created_at');

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('product_reviews.is_visible', false);
            } elseif ($request->status === 'approved') {
                $query->where('product_reviews.is_visible', true);
            }
        }

        $reviews = $query->paginate(20)->withQueryString();

        return view('admin.reviews.index', [
            'reviews' => $reviews,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'is_visible' => ['required', 'boolean'],
        ]);

        DB::table('product_reviews')
            ->where('id', $id)
            ->update([
                'is_visible' => $validated['is_visible'],
                'updated_at' => now(),
            ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Review status updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        DB::table('review_helpfulness')->where('review_id', $id)->delete();
        DB::table('product_reviews')->where('id', $id)->delete();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Review deleted successfully.',
        ]);
    }
}
