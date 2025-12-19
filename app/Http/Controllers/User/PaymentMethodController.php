<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = DB::table('payment_methods')
            ->where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return view('user.payment-methods.index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider' => ['required', 'string', 'max:60', 'in:card,paypal,stripe'],
            'provider_reference' => ['required', 'string', 'max:120'],
            'brand' => ['nullable', 'string', 'max:60'],
            'last4' => ['nullable', 'string', 'size:4'],
            'expires_on' => ['nullable', 'date'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        // If setting as default, unset other defaults
        if ($request->boolean('is_default')) {
            DB::table('payment_methods')
                ->where('user_id', Auth::id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        DB::table('payment_methods')->insert([
            'user_id' => Auth::id(),
            'provider' => $validated['provider'],
            'provider_reference' => $validated['provider_reference'],
            'brand' => $validated['brand'] ?? null,
            'last4' => $validated['last4'] ?? null,
            'expires_on' => $validated['expires_on'] ?? null,
            'is_default' => $request->boolean('is_default'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Payment method added successfully.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $paymentMethod = DB::table('payment_methods')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$paymentMethod) {
            abort(404);
        }

        $validated = $request->validate([
            'is_default' => ['nullable', 'boolean'],
        ]);

        // If setting as default, unset other defaults
        if ($request->boolean('is_default')) {
            DB::table('payment_methods')
                ->where('user_id', Auth::id())
                ->where('id', '!=', $id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        DB::table('payment_methods')
            ->where('id', $id)
            ->update([
                'is_default' => $request->boolean('is_default'),
                'updated_at' => now(),
            ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Payment method updated successfully.',
        ]);
    }

    public function destroy($id)
    {
        $paymentMethod = DB::table('payment_methods')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$paymentMethod) {
            abort(404);
        }

        DB::table('payment_methods')->where('id', $id)->delete();

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Payment method deleted successfully.',
        ]);
    }
}
