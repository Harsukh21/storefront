<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = DB::table('brands')
            ->select('id', 'name', 'slug', 'is_active', 'created_at')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.catalog.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.catalog.brands.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:brands,slug'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $brandId = DB::table('brands')->insertGetId([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'logo_path' => null,
            'is_active' => $request->boolean('is_active'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CacheService::clearCatalogCache();
        Log::info('Brand created', ['brand_id' => $brandId, 'name' => $data['name'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Brand created successfully.']);

        return redirect()->route('admin.catalog.brands.index');
    }

    public function edit(int $brand)
    {
        $brandRecord = DB::table('brands')->where('id', $brand)->first();

        if (!$brandRecord) {
            abort(404);
        }

        return view('admin.catalog.brands.edit', ['brand' => $brandRecord]);
    }

    public function update(Request $request, int $brand)
    {
        $brandRecord = DB::table('brands')->where('id', $brand)->first();
        if (!$brandRecord) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:191', 'unique:brands,slug,' . $brand],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        DB::table('brands')->where('id', $brand)->update([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'description' => $data['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'updated_at' => now(),
        ]);

        CacheService::clearCatalogCache();
        Log::info('Brand updated', ['brand_id' => $brand, 'name' => $data['name'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Brand updated successfully.']);

        return redirect()->route('admin.catalog.brands.index');
    }

    public function destroy(int $brand)
    {
        $brandRecord = DB::table('brands')->where('id', $brand)->first();
        DB::table('brands')->where('id', $brand)->delete();

        CacheService::clearCatalogCache();
        Log::warning('Brand deleted', ['brand_id' => $brand, 'name' => $brandRecord->name ?? 'Unknown', 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Brand deleted.']);

        return redirect()->route('admin.catalog.brands.index');
    }
}
