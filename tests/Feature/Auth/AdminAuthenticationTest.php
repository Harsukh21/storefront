<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret1234'),
        ]);

        $response = $this->post(route('admin.login.attempt'), [
            'email' => $admin->email,
            'password' => 'secret1234',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_login_with_invalid_credentials_returns_errors(): void
    {
        Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret1234'),
        ]);

        $response = $this->post(route('admin.login.attempt'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(auth('admin')->check());
    }

    public function test_admin_can_request_password_reset_link(): void
    {
        Notification::fake();

        $admin = Admin::create([
            'name' => 'Reset Admin',
            'email' => 'reset@example.com',
            'password' => Hash::make('secret1234'),
        ]);

        $response = $this->post(route('admin.password.email'), [
            'email' => $admin->email,
        ]);

        $response->assertSessionHas('status');
        Notification::assertSentTo($admin, ResetPassword::class);
    }
}
