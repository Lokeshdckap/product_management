<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, function ($event) {
            if ($event->user && method_exists($event->user, 'update')) {
                $event->user->update([
                    'is_online' => true,
                    'last_seen_at' => now()
                ]);
            }
        });

        Event::listen(Logout::class, function ($event) {
            if ($event->user && method_exists($event->user, 'update')) {
                $event->user->update([
                    'is_online' => false,
                    'last_seen_at' => now()
                ]);
            }
        });

        // Event::listen(Authenticated::class, function ($event) {
        //     if ($event->user && method_exists($event->user, 'update')) {
        //         $event->user->update([
        //             'is_online' => true,
        //             'last_seen_at' => now()
        //         ]);
        //     }
        // });
    }
}
