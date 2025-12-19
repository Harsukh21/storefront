@extends('layouts.admin-panel')

@section('title', 'Product Questions - ' . config('app.name') . ' Admin')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Product Questions</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Answer customer questions about products</p>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-6 p-6">
        <form method="GET" class="flex gap-4">
            <div class="flex-1">
                <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Status</label>
                <select 
                    name="status" 
                    onchange="this.form.submit()" 
                    class="w-full rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100"
                >
                    <option value="">All Questions</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending Approval</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    <div class="space-y-6">
        @forelse($questions as $question)
            <div class="admin-card p-6">
                <div class="mb-4 flex items-start justify-between">
                    <div class="flex-1">
                        <a 
                            href="{{ route('front.products.show', $question->product_slug) }}" 
                            class="text-lg font-bold text-sf-accent-primary transition-colors hover:text-sf-accent-secondary"
                        >
                            {{ $question->product_name }}
                        </a>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Asked by <span class="font-semibold">{{ $question->user_name ?? 'Anonymous' }}</span> on {{ \Carbon\Carbon::parse($question->created_at)->format('M d, Y') }}
                        </p>
                    </div>
                    <span class="ml-4 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $question->is_visible ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500' }}">
                        {{ $question->is_visible ? 'Approved' : 'Pending' }}
                    </span>
                </div>
                <p class="mb-6 text-sm font-medium text-slate-900 dark:text-slate-100">{{ $question->question }}</p>
                
                @if(isset($question->answers) && $question->answers->count() > 0)
                    <div class="mb-6 border-t border-sf-border/60 pt-6 dark:border-[rgba(114,138,221,0.25)]">
                        <h4 class="mb-4 text-sm font-bold text-slate-900 dark:text-slate-100">Answers</h4>
                        <div class="space-y-3">
                            @foreach($question->answers as $answer)
                                <div class="rounded-lg border border-sf-border/60 bg-slate-50 p-4 dark:border-[rgba(114,138,221,0.25)] dark:bg-sf-night-900/50">
                                    <p class="text-sm text-slate-700 dark:text-slate-200">{{ $answer->answer }}</p>
                                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                        Answered by <span class="font-semibold">{{ $answer->admin_name ?? ($answer->user_name ?? 'Admin') }}</span> on {{ \Carbon\Carbon::parse($answer->created_at)->format('M d, Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex flex-wrap gap-3 border-t border-sf-border/60 pt-6 dark:border-[rgba(114,138,221,0.25)]">
                    <form method="POST" action="{{ route('admin.questions.answer', $question->id) }}" class="flex-1 min-w-[300px]">
                        @csrf
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                name="answer" 
                                placeholder="Type your answer..." 
                                required 
                                class="flex-1 rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm transition-all focus:border-sf-accent-primary focus:outline-none focus:ring-2 focus:ring-sf-accent-primary/20 dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-700 dark:text-slate-100" 
                            />
                            <button 
                                type="submit" 
                                class="rounded-lg bg-gradient-to-r from-sf-accent-primary to-sf-accent-secondary px-4 py-2 text-sm font-semibold text-white shadow-lg transition-all hover:opacity-90 hover:shadow-xl dark:text-sf-night-900"
                            >
                                Answer
                            </button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('admin.questions.update', $question->id) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_visible" value="{{ $question->is_visible ? '0' : '1' }}" />
                        <button 
                            type="submit" 
                            class="rounded-lg border border-sf-border/60 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:border-sf-accent-primary dark:border-[rgba(114,138,221,0.35)] dark:bg-sf-night-800 dark:text-slate-200"
                        >
                            {{ $question->is_visible ? 'Hide' : 'Approve' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.questions.destroy', $question->id) }}" onsubmit="return confirm('Delete this question?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="rounded-lg border border-red-500/50 bg-white px-4 py-2 text-sm font-semibold text-red-500 transition-colors hover:bg-red-50 dark:border-red-900/50 dark:bg-sf-night-800 dark:hover:bg-red-900/20"
                        >
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="admin-card p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-slate-100">No questions found</h3>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Try adjusting your filter criteria.</p>
            </div>
        @endforelse
    </div>
    @if($questions->hasPages())
        <div class="mt-6">
            {{ $questions->links() }}
        </div>
    @endif
</div>
@endsection
