<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('categories')
            ->select('id', 'name', 'slug', 'parent_id', 'is_active', 'created_at')
            ->orderByDesc('created_at')
            ->paginate(12);

        $parentNames = DB::table('categories')
            ->whereIn('id', $categories->pluck('parent_id')->filter())
            ->pluck('name', 'id');

        return view('admin.catalog.categories.index', [
            'categories' => $categories,
            'parentNames' => $parentNames,
        ]);
    }

    public function create()
    {
        $categories = DB::table('categories')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('admin.catalog.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:categories,slug'],
            'parent_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['parent_id'])) {
            $exists = DB::table('categories')->where('id', $data['parent_id'])->exists();
            if (!$exists) {
                return back()->withErrors(['parent_id' => 'Selected parent category does not exist.'])->withInput();
            }
        }

        $categoryId = DB::table('categories')->insertGetId([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'parent_id' => $data['parent_id'] ?: null,
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'meta_title' => null,
            'meta_description' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CacheService::clearCatalogCache();
        Log::info('Category created', ['category_id' => $categoryId, 'name' => $data['name'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Category created successfully.']);

        return redirect()->route('admin.catalog.categories.index');
    }

    public function edit(int $category)
    {
        $categoryRecord = DB::table('categories')->where('id', $category)->first();

        if (!$categoryRecord) {
            abort(404);
        }

        $categories = DB::table('categories')
            ->select('id', 'name')
            ->where('id', '!=', $category)
            ->orderBy('name')
            ->get();

        return view('admin.catalog.categories.edit', [
            'category' => $categoryRecord,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, int $category)
    {
        $categoryRecord = DB::table('categories')->where('id', $category)->first();
        if (!$categoryRecord) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:categories,slug,' . $category],
            'parent_id' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['parent_id'])) {
            if ($data['parent_id'] == $category) {
                return back()->withErrors(['parent_id' => 'A category cannot be its own parent.'])->withInput();
            }
            $exists = DB::table('categories')->where('id', $data['parent_id'])->exists();
            if (!$exists) {
                return back()->withErrors(['parent_id' => 'Selected parent category does not exist.'])->withInput();
            }
        }

        DB::table('categories')->where('id', $category)->update([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'parent_id' => $data['parent_id'] ?: null,
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'updated_at' => now(),
        ]);

        CacheService::clearCatalogCache();
        Log::info('Category updated', ['category_id' => $category, 'name' => $data['name'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Category updated successfully.']);

        return redirect()->route('admin.catalog.categories.index');
    }

    public function destroy(int $category)
    {
        $categoryRecord = DB::table('categories')->where('id', $category)->first();
        DB::table('categories')->where('id', $category)->delete();

        CacheService::clearCatalogCache();
        Log::warning('Category deleted', ['category_id' => $category, 'name' => $categoryRecord->name ?? 'Unknown', 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Category deleted.']);

        return redirect()->route('admin.catalog.categories.index');
    }
}
