<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\BroadcastAuthenticate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/admin.php',
            __DIR__.'/../routes/customer.php',
        ],
        channels: __DIR__.'/../routes/channels.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
   ->withMiddleware(function (Middleware $middleware): void {

    $middleware->alias([
        'broadcast.auth' => BroadcastAuthenticate::class,
    ]);

    // Redirect Guests
    $middleware->redirectGuestsTo(function ($request) {
        // Guest trying admin pages
        if ($request->is('admin/*')) {
            return route('admin.login');
        }

        // Guest hitting root or customer pages
        if ($request->is('/') || in_array($request->path(), ['login', 'register'])) {
            return route('login'); // customer login
        }

        return '/';
    });

    // Redirect Logged-in Users
    $middleware->redirectUsersTo(function ($request) {
        // Logged-in admin
        if ($request->is('admin/*')) {
            return route('admin.dashboard');
        }

        // Logged-in customer
        if ($request->is('/') || in_array($request->path(), ['login', 'register', ''])) {
            return route('home'); // customer home
        }

        return '/';
    });

})

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
