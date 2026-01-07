<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard("admin")->check()) {
            Auth::shouldUse("admin");
            return $next($request);
        }

        if (Auth::guard("customer")->check()) {
            Auth::shouldUse("customer");
            return $next($request);
        }

        abort(403);
    }
}
