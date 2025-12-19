<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Check if this is an admin route
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            // Default to user login
            return route('user.login');
        }
        
        return null;
    }
}

