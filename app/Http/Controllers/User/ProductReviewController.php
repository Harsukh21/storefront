<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReviewController extends Controller
{
    public function store(Request $request, string $slug)
    {
        $product = DB::table('products')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            abort(404);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['nullable', 'string', 'max:1000'],
        ]);

        $existing = DB::table('product_reviews')
            ->where('product_id', $product->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            DB::table('product_reviews')
                ->where('id', $existing->id)
                ->update([
                    'rating' => $data['rating'],
                    'title' => $data['title'],
                    'body' => $data['body'],
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('product_reviews')->insert([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'rating' => $data['rating'],
                'title' => $data['title'],
                'body' => $data['body'],
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        session()->flash('toast', ['type' => 'success', 'message' => 'Thanks for sharing your review.']);

        return back();
    }
}
