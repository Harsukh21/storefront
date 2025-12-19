<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('admins')
            ->select('id', 'name', 'email', 'role', 'last_login_at', 'created_at')
            ->orderByDesc('created_at');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $admins = $query->paginate(20)->withQueryString();

        return view('admin.admins.index', [
            'admins' => $admins,
        ]);
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:191', 'unique:admins,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'in:admin,manager'],
        ]);

        $adminId = DB::table('admins')->insertGetId([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Admin created', ['admin_id' => $adminId, 'email' => $validated['email'], 'created_by' => auth('admin')->id()]);

        return redirect()->route('admin.admins.index')->with('toast', [
            'type' => 'success',
            'message' => 'Admin created successfully.',
        ]);
    }

    public function show($id)
    {
        $admin = DB::table('admins')->where('id', $id)->first();

        if (!$admin) {
            abort(404);
        }

        return view('admin.admins.show', [
            'admin' => $admin,
        ]);
    }

    public function edit($id)
    {
        $admin = DB::table('admins')->where('id', $id)->first();

        if (!$admin) {
            abort(404);
        }

        return view('admin.admins.edit', [
            'admin' => $admin,
        ]);
    }

    public function update(Request $request, $id)
    {
        $admin = DB::table('admins')->where('id', $id)->first();

        if (!$admin) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:191', 'unique:admins,email,' . $id],
            'role' => ['required', 'string', 'in:admin,manager'],
        ]);

        DB::table('admins')
            ->where('id', $id)
            ->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'updated_at' => now(),
            ]);

        Log::info('Admin updated', ['admin_id' => $id, 'updated_by' => auth('admin')->id()]);

        return redirect()->route('admin.admins.index')->with('toast', [
            'type' => 'success',
            'message' => 'Admin updated successfully.',
        ]);
    }

    public function updatePassword(Request $request, $id)
    {
        $admin = DB::table('admins')->where('id', $id)->first();

        if (!$admin) {
            abort(404);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        DB::table('admins')
            ->where('id', $id)
            ->update([
                'password' => Hash::make($validated['password']),
                'updated_at' => now(),
            ]);

        Log::warning('Admin password reset', ['admin_id' => $id, 'reset_by' => auth('admin')->id()]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Admin password reset successfully.',
        ]);
    }

    public function destroy($id)
    {
        $admin = DB::table('admins')->where('id', $id)->first();

        if (!$admin) {
            abort(404);
        }

        // Prevent self-deletion
        if ($id == auth('admin')->id()) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'You cannot delete your own account.',
            ]);
        }

        DB::table('admins')->where('id', $id)->delete();

        Log::warning('Admin deleted', ['admin_id' => $id, 'email' => $admin->email, 'deleted_by' => auth('admin')->id()]);

        return redirect()->route('admin.admins.index')->with('toast', [
            'type' => 'success',
            'message' => 'Admin deleted successfully.',
        ]);
    }
}
