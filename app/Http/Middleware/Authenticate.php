<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        // Check if the request expects JSON response
        if ($request->expectsJson()) {
            return null; // If JSON response is expected, do not redirect
        } else {
            // If not, redirect to the login route
            return route('login');
        }
    }
    
}
