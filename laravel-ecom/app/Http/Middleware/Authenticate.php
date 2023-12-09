<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        // Check if the request is for the logout route
        if ($request->is('logout')) {
            return null; // Return null to avoid redirection for logout route
        }

        // For other cases, return the default redirection (if needed)
        return $request->expectsJson() ? null : route('login');
    }
}
