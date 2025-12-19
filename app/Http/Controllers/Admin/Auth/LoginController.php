<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\AdminTwoFactorCodeNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $admin = Auth::guard('admin')->user();

            if ($admin && $admin->two_factor_enabled) {
                $remember = $request->boolean('remember');
                $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                DB::table('admin_two_factor_codes')->where('admin_id', $admin->id)->delete();
                DB::table('admin_two_factor_codes')->insert([
                    'admin_id' => $admin->id,
                    'code' => $code,
                    'expires_at' => Carbon::now()->addMinutes(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                Notification::route('mail', $admin->email)->notify(new AdminTwoFactorCodeNotification($code, 10));

                Auth::guard('admin')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $request->session()->put('two_factor:admin:id', $admin->id);
                $request->session()->put('two_factor:admin:email', $admin->email);
                $request->session()->put('two_factor:admin:remember', $remember);

                return redirect()->route('admin.two-factor.challenge')->with('status', 'We sent a verification code to your email.');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
