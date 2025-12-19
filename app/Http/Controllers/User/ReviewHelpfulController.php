<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewHelpfulController extends Controller
{
    public function store(Request $request, $reviewId)
    {
        $validated = $request->validate([
            'is_helpful' => ['required', 'boolean'],
        ]);

        // Check if user already voted
        $existing = DB::table('review_helpfulness')
            ->where('review_id', $reviewId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            // Update existing vote
            DB::table('review_helpfulness')
                ->where('id', $existing->id)
                ->update([
                    'is_helpful' => $validated['is_helpful'],
                ]);
        } else {
            // Create new vote
            DB::table('review_helpfulness')->insert([
                'review_id' => $reviewId,
                'user_id' => Auth::id(),
                'is_helpful' => $validated['is_helpful'],
                'created_at' => now(),
            ]);
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Thank you for your feedback.',
        ]);
    }
}
