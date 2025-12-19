<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('users')
            ->select('id', 'name', 'email', 'phone', 'email_verified_at', 'created_at', 'last_login_at')
            ->orderByDesc('created_at');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('phone', 'ILIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function show($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        // Get user statistics
        $orderCount = DB::table('orders')->where('user_id', $id)->count();
        $totalSpent = DB::table('orders')
            ->where('user_id', $id)
            ->where('payment_status', 'paid')
            ->sum('grand_total') ?? 0;

        $wishlistCount = DB::table('wishlist_items')
            ->leftJoin('wishlists', 'wishlist_items.wishlist_id', '=', 'wishlists.id')
            ->where('wishlists.user_id', $id)
            ->count();

        $addressCount = DB::table('user_addresses')->where('user_id', $id)->count();

        return view('admin.users.show', [
            'user' => $user,
            'orderCount' => $orderCount,
            'totalSpent' => $totalSpent,
            'wishlistCount' => $wishlistCount,
            'addressCount' => $addressCount,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email,' . $id],
            'phone' => ['nullable', 'string', 'max:40'],
        ]);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'updated_at' => now(),
            ]);

        Log::info('User updated by admin', ['user_id' => $id, 'admin_id' => auth('admin')->id()]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'User updated successfully.',
        ]);
    }

    public function updatePassword(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'password' => Hash::make($validated['password']),
                'updated_at' => now(),
            ]);

        Log::warning('User password reset by admin', ['user_id' => $id, 'admin_id' => auth('admin')->id()]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'User password reset successfully.',
        ]);
    }

    public function destroy($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        // Don't allow deleting if user has orders
        $hasOrders = DB::table('orders')->where('user_id', $id)->exists();
        if ($hasOrders) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Cannot delete user with existing orders.',
            ]);
        }

        DB::table('users')->where('id', $id)->delete();

        Log::warning('User deleted by admin', ['user_id' => $id, 'email' => $user->email, 'admin_id' => auth('admin')->id()]);

        return redirect()->route('admin.users.index')->with('toast', [
            'type' => 'success',
            'message' => 'User deleted successfully.',
        ]);
    }
}
