<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('discounts')->orderByDesc('created_at');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'ILIKE', "%{$search}%")
                    ->orWhere('type', 'ILIKE', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $discounts = $query->paginate(15)->withQueryString();

        // Get redemption counts
        foreach ($discounts as $discount) {
            $discount->redemption_count = DB::table('discount_redemptions')
                ->where('discount_id', $discount->id)
                ->count();
        }

        return view('admin.discounts.index', [
            'discounts' => $discounts,
            'filters' => [
                'search' => $request->query('search'),
                'status' => $request->query('status'),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:60', 'unique:discounts,code'],
            'type' => ['required', 'string', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'minimum_subtotal' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Adjust value for percentage
        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage cannot exceed 100%'])->withInput();
        }

        $discountId = DB::table('discounts')->insertGetId([
            'code' => Str::upper($data['code']),
            'type' => $data['type'],
            'value' => $data['value'],
            'minimum_subtotal' => $data['minimum_subtotal'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'usage_limit_per_user' => $data['usage_limit_per_user'] ?? null,
            'starts_at' => $data['starts_at'] ? now()->parse($data['starts_at']) : null,
            'expires_at' => $data['expires_at'] ? now()->parse($data['expires_at']) : null,
            'is_active' => $request->boolean('is_active', true),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Discount created', ['discount_id' => $discountId, 'code' => $data['code'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Discount created successfully.']);

        return redirect()->route('admin.discounts.index');
    }

    public function edit(int $discount)
    {
        $discountRecord = DB::table('discounts')->where('id', $discount)->first();
        if (!$discountRecord) {
            abort(404);
        }

        $redemptionCount = DB::table('discount_redemptions')
            ->where('discount_id', $discount)
            ->count();

        return view('admin.discounts.edit', [
            'discount' => $discountRecord,
            'redemptionCount' => $redemptionCount,
        ]);
    }

    public function update(Request $request, int $discount)
    {
        $discountRecord = DB::table('discounts')->where('id', $discount)->first();
        if (!$discountRecord) {
            abort(404);
        }

        $data = $request->validate([
            'code' => ['required', 'string', 'max:60', 'unique:discounts,code,' . $discount],
            'type' => ['required', 'string', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'minimum_subtotal' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($data['type'] === 'percentage' && $data['value'] > 100) {
            return back()->withErrors(['value' => 'Percentage cannot exceed 100%'])->withInput();
        }

        DB::table('discounts')->where('id', $discount)->update([
            'code' => Str::upper($data['code']),
            'type' => $data['type'],
            'value' => $data['value'],
            'minimum_subtotal' => $data['minimum_subtotal'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'usage_limit_per_user' => $data['usage_limit_per_user'] ?? null,
            'starts_at' => $data['starts_at'] ? now()->parse($data['starts_at']) : null,
            'expires_at' => $data['expires_at'] ? now()->parse($data['expires_at']) : null,
            'is_active' => $request->boolean('is_active', true),
            'updated_at' => now(),
        ]);

        Log::info('Discount updated', ['discount_id' => $discount, 'code' => $data['code'], 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Discount updated successfully.']);

        return redirect()->route('admin.discounts.index');
    }

    public function destroy(int $discount)
    {
        $discountRecord = DB::table('discounts')->where('id', $discount)->first();
        if (!$discountRecord) {
            abort(404);
        }

        DB::table('discounts')->where('id', $discount)->delete();

        Log::warning('Discount deleted', ['discount_id' => $discount, 'code' => $discountRecord->code, 'admin_id' => auth('admin')->id()]);

        session()->flash('toast', ['type' => 'success', 'message' => 'Discount deleted successfully.']);

        return redirect()->route('admin.discounts.index');
    }
}


