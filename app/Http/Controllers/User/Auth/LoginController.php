<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\UserTwoFactorCodeNotification;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('web')->user();

            if ($user && $user->two_factor_enabled) {
                $remember = $request->boolean('remember');
                $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

                DB::table('user_two_factor_codes')->where('user_id', $user->id)->delete();
                DB::table('user_two_factor_codes')->insert([
                    'user_id' => $user->id,
                    'code' => $code,
                    'expires_at' => Carbon::now()->addMinutes(10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                Notification::route('mail', $user->email)->notify(new UserTwoFactorCodeNotification($code, 10));

                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $request->session()->put('two_factor:user:id', $user->id);
                $request->session()->put('two_factor:user:email', $user->email);
                $request->session()->put('two_factor:user:remember', $remember);

                return redirect()->route('user.two-factor.challenge')->with('status', 'We sent a verification code to your email.');
            }

            $request->session()->regenerate();

            // Merge session cart into user cart
            $cartService = app(CartService::class);
            $cartService->mergeSessionCart($user->id);

            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('user.login');
    }
}
