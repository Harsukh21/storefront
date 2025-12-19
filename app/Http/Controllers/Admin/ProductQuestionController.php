<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductQuestionController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('product_questions')
            ->leftJoin('products', 'product_questions.product_id', '=', 'products.id')
            ->leftJoin('users', 'product_questions.user_id', '=', 'users.id')
            ->select(
                'product_questions.*',
                'products.name as product_name',
                'products.slug as product_slug',
                'users.name as user_name',
                'users.email as user_email'
            )
            ->orderByDesc('product_questions.created_at');

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('product_questions.is_visible', false);
            } elseif ($request->status === 'approved') {
                $query->where('product_questions.is_visible', true);
            }
        }

        $questions = $query->get();

        // Get answers for each question
        foreach ($questions as $question) {
            $question->answers = DB::table('product_answers')
                ->leftJoin('admins', 'product_answers.admin_id', '=', 'admins.id')
                ->leftJoin('users', 'product_answers.user_id', '=', 'users.id')
                ->where('product_answers.question_id', $question->id)
                ->where('product_answers.is_visible', true)
                ->select('product_answers.*', 'admins.name as admin_name', 'users.name as user_name')
                ->orderBy('product_answers.created_at')
                ->get();
        }

        // Convert to paginator manually
        $currentPage = $request->get('page', 1);
        $perPage = 20;
        $total = DB::table('product_questions')
            ->when($request->filled('status'), function ($q) use ($request) {
                if ($request->status === 'pending') {
                    $q->where('is_visible', false);
                } elseif ($request->status === 'approved') {
                    $q->where('is_visible', true);
                }
            })
            ->count();

        $questions = new \Illuminate\Pagination\LengthAwarePaginator(
            $questions->forPage($currentPage, $perPage),
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.questions.index', [
            'questions' => $questions,
        ]);
    }

    public function answer(Request $request, $id)
    {
        $question = DB::table('product_questions')->where('id', $id)->first();

        if (!$question) {
            abort(404);
        }

        $validated = $request->validate([
            'answer' => ['required', 'string', 'max:1000'],
        ]);

        DB::table('product_answers')->insert([
            'question_id' => $id,
            'admin_id' => Auth::guard('admin')->id(),
            'user_id' => null,
            'answer' => $validated['answer'],
            'is_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Auto-approve the question when answered
        DB::table('product_questions')
            ->where('id', $id)
            ->update([
                'is_visible' => true,
                'updated_at' => now(),
            ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Answer posted successfully.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'is_visible' => ['required', 'boolean'],
        ]);

        DB::table('product_questions')
            ->where('id', $id)
            ->update([
                'is_visible' => $validated['is_visible'],
                'updated_at' => now(),
            ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Question status updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        DB::table('product_answers')->where('question_id', $id)->delete();
        DB::table('product_questions')->where('id', $id)->delete();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Question deleted successfully.',
        ]);
    }
}

