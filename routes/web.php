<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Customer\PresenceController;

Broadcast::routes([
    'middleware' => ['web', 'broadcast.auth'],
]);

Route::get('/', function () {
    if (auth('customer')->check()) {
        return redirect()->route('home');
    }

    if (auth('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});






