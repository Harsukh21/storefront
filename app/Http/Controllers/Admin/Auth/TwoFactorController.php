<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\AdminTwoFactorCodeNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TwoFactorController extends Controller
{
    public function create(Request $request)
    {
        if (!$request->session()->has('two_factor:admin:id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.two-factor');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $adminId = $request->session()->get('two_factor:admin:id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $record = DB::table('admin_two_factor_codes')
            ->where('admin_id', $adminId)
            ->latest('created_at')
            ->first();

        if (!$record || $record->expires_at < Carbon::now() || $record->code !== $request->code) {
            return back()->withErrors([
                'code' => 'The authentication code is invalid or has expired.',
            ]);
        }

        DB::table('admin_two_factor_codes')->where('admin_id', $adminId)->delete();

        Auth::guard('admin')->loginUsingId($adminId, $request->session()->pull('two_factor:admin:remember', false));
        $request->session()->forget(['two_factor:admin:id', 'two_factor:admin:email']);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $adminId = $request->session()->get('two_factor:admin:id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $admin = DB::table('admins')->where('id', $adminId)->first();
        if (!$admin) {
            return redirect()->route('admin.login');
        }

        DB::table('admin_two_factor_codes')->where('admin_id', $adminId)->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(10);

        DB::table('admin_two_factor_codes')->insert([
            'admin_id' => $adminId,
            'code' => $code,
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Notification::route('mail', $admin->email)->notify(new AdminTwoFactorCodeNotification($code, 10));

        return back()->with('status', 'We have re-sent your authentication code.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $request->session()->forget(['two_factor:admin:id', 'two_factor:admin:email', 'two_factor:admin:remember']);

        return redirect()->route('admin.login');
    }
}
