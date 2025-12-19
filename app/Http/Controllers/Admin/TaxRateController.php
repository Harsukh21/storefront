<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaxRateController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('tax_rates')->orderBy('priority')->orderBy('name');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('country', 'ILIKE', "%{$search}%")
                    ->orWhere('state', 'ILIKE', "%{$search}%");
            });
        }

        $taxRates = $query->paginate(15)->withQueryString();

        return view('admin.tax-rates.index', [
            'taxRates' => $taxRates,
            'filters' => [
                'search' => $request->query('search'),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.tax-rates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'country' => ['nullable', 'string', 'size:2'],
            'state' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:120'],
            'priority' => ['nullable', 'integer', 'min:0'],
            'compound' => ['nullable', 'boolean'],
        ]);

        DB::table('tax_rates')->insert([
            'name' => $data['name'],
            'rate' => $data['rate'],
            'country' => $data['country'] ?? null,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'priority' => $data['priority'] ?? 0,
            'compound' => $request->boolean('compound', false),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Tax rate created', ['name' => $data['name'], 'rate' => $data['rate'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Tax rate created successfully.']);

        return redirect()->route('admin.tax-rates.index');
    }

    public function edit(int $taxRate)
    {
        $taxRateRecord = DB::table('tax_rates')->where('id', $taxRate)->first();
        if (!$taxRateRecord) {
            abort(404);
        }

        return view('admin.tax-rates.edit', [
            'taxRate' => $taxRateRecord,
        ]);
    }

    public function update(Request $request, int $taxRate)
    {
        $taxRateRecord = DB::table('tax_rates')->where('id', $taxRate)->first();
        if (!$taxRateRecord) {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'rate' => ['required', 'numeric', 'min:0', 'max:1'],
            'country' => ['nullable', 'string', 'size:2'],
            'state' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'city' => ['nullable', 'string', 'max:120'],
            'priority' => ['nullable', 'integer', 'min:0'],
            'compound' => ['nullable', 'boolean'],
        ]);

        DB::table('tax_rates')->where('id', $taxRate)->update([
            'name' => $data['name'],
            'rate' => $data['rate'],
            'country' => $data['country'] ?? null,
            'state' => $data['state'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'priority' => $data['priority'] ?? 0,
            'compound' => $request->boolean('compound', false),
            'updated_at' => now(),
        ]);

        Log::info('Tax rate updated', ['tax_rate_id' => $taxRate, 'name' => $data['name'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Tax rate updated successfully.']);

        return redirect()->route('admin.tax-rates.index');
    }

    public function destroy(int $taxRate)
    {
        $taxRateRecord = DB::table('tax_rates')->where('id', $taxRate)->first();
        if (!$taxRateRecord) {
            abort(404);
        }

        DB::table('tax_rates')->where('id', $taxRate)->delete();

        Log::warning('Tax rate deleted', ['tax_rate_id' => $taxRate, 'name' => $taxRateRecord->name, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Tax rate deleted successfully.']);

        return redirect()->route('admin.tax-rates.index');
    }
}


