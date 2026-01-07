<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BroadcastAuthenticate
{
     public function handle($request, Closure $next)
    {
        // Admins can join any channel
        if (Auth::guard('admin')->check()) {
            Auth::shouldUse('admin');
            return $next($request);
        }

        // Customers can only join customer channels
        if (Auth::guard('customer')->check()) {
            Auth::shouldUse('customer');
            return $next($request);
        }

        abort(403);
    }
}


