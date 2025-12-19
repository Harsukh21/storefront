<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\UserTwoFactorCodeNotification;
use App\Services\CartService;
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
        if (!$request->session()->has('two_factor:user:id')) {
            return redirect()->route('user.login');
        }

        return view('user.auth.two-factor');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = $request->session()->get('two_factor:user:id');
        if (!$userId) {
            return redirect()->route('user.login');
        }

        $record = DB::table('user_two_factor_codes')
            ->where('user_id', $userId)
            ->latest('created_at')
            ->first();

        if (!$record || $record->expires_at < Carbon::now() || $record->code !== $request->code) {
            return back()->withErrors([
                'code' => 'The authentication code is invalid or has expired.',
            ]);
        }

        DB::table('user_two_factor_codes')->where('user_id', $userId)->delete();

        Auth::guard('web')->loginUsingId($userId, $request->session()->pull('two_factor:user:remember', false));
        $request->session()->forget(['two_factor:user:id', 'two_factor:user:email']);
        $request->session()->regenerate();

        // Merge session cart into user cart
        $cartService = app(CartService::class);
        $cartService->mergeSessionCart($userId);

        return redirect()->intended(route('user.dashboard'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('two_factor:user:id');
        if (!$userId) {
            return redirect()->route('user.login');
        }

        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) {
            return redirect()->route('user.login');
        }

        DB::table('user_two_factor_codes')->where('user_id', $userId)->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(10);

        DB::table('user_two_factor_codes')->insert([
            'user_id' => $userId,
            'code' => $code,
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Notification::route('mail', $user->email)->notify(new UserTwoFactorCodeNotification($code, 10));

        return back()->with('status', 'We have re-sent your authentication code.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        $request->session()->forget(['two_factor:user:id', 'two_factor:user:email', 'two_factor:user:remember']);

        return redirect()->route('user.login');
    }
}
