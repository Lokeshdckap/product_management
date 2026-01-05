<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:[
            __DIR__.'/../routes/web.php',
            __DIR__.'/../routes/admin.php',
            __DIR__.'/../routes/customer.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function ($request) {

    // if ($request->routeIs('customer.*')) {
    //     return route('customer.login');
    // }

    // if ($request->routeIs('admin.*')) {
    //     return route('admin.login');
    // }

    // return route('login');
});

    })
    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
