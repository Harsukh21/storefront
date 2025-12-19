<?php

namespace App\Http\Controllers\FrontWebsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductQuestionController extends Controller
{
    public function store(Request $request, $productSlug)
    {
        $product = DB::table('products')
            ->where('slug', $productSlug)
            ->where('is_active', true)
            ->first();

        if (!$product) {
            abort(404);
        }

        $validated = $request->validate([
            'question' => ['required', 'string', 'max:1000'],
        ]);

        DB::table('product_questions')->insert([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'question' => $validated['question'],
            'is_visible' => false, // Requires moderation
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Your question has been submitted and will be reviewed.',
        ]);
    }
}
