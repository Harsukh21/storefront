<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = DB::table('user_addresses')
            ->where('user_id', Auth::id())
            ->orderByDesc('is_default_shipping')
            ->orderByDesc('is_default_billing')
            ->orderByDesc('created_at')
            ->get();

        return view('user.addresses.index', [
            'addresses' => $addresses,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'recipient_name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'size:2'],
            'is_default_shipping' => ['nullable', 'boolean'],
            'is_default_billing' => ['nullable', 'boolean'],
        ]);

        // If setting as default, unset other defaults
        if ($request->boolean('is_default_shipping')) {
            DB::table('user_addresses')
                ->where('user_id', Auth::id())
                ->where('is_default_shipping', true)
                ->update(['is_default_shipping' => false]);
        }

        if ($request->boolean('is_default_billing')) {
            DB::table('user_addresses')
                ->where('user_id', Auth::id())
                ->where('is_default_billing', true)
                ->update(['is_default_billing' => false]);
        }

        DB::table('user_addresses')->insert([
            'user_id' => Auth::id(),
            'label' => $validated['label'] ?? null,
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'] ?? null,
            'line1' => $validated['line1'],
            'line2' => $validated['line2'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'country' => $validated['country'],
            'is_default_shipping' => $request->boolean('is_default_shipping'),
            'is_default_billing' => $request->boolean('is_default_billing'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Address added successfully.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $address = DB::table('user_addresses')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) {
            abort(404);
        }

        $validated = $request->validate([
            'label' => ['nullable', 'string', 'max:100'],
            'recipient_name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:40'],
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:30'],
            'country' => ['required', 'string', 'size:2'],
            'is_default_shipping' => ['nullable', 'boolean'],
            'is_default_billing' => ['nullable', 'boolean'],
        ]);

        // If setting as default, unset other defaults
        if ($request->boolean('is_default_shipping')) {
            DB::table('user_addresses')
                ->where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->where('is_default_shipping', true)
                ->update(['is_default_shipping' => false]);
        }

        if ($request->boolean('is_default_billing')) {
            DB::table('user_addresses')
                ->where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->where('is_default_billing', true)
                ->update(['is_default_billing' => false]);
        }

        DB::table('user_addresses')
            ->where('id', $id)
            ->update([
                'label' => $validated['label'] ?? null,
                'recipient_name' => $validated['recipient_name'],
                'phone' => $validated['phone'] ?? null,
                'line1' => $validated['line1'],
                'line2' => $validated['line2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'country' => $validated['country'],
                'is_default_shipping' => $request->boolean('is_default_shipping'),
                'is_default_billing' => $request->boolean('is_default_billing'),
                'updated_at' => now(),
            ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Address updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $address = DB::table('user_addresses')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) {
            abort(404);
        }

        DB::table('user_addresses')->where('id', $id)->delete();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Address deleted successfully.',
        ]);
    }
}
