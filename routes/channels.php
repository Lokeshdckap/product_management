<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Broadcasting Routes
|--------------------------------------------------------------------------
*/
Broadcast::routes([
    'middleware' => ['web', 'broadcast.auth'],
]);

Broadcast::channel('presence-admins', function () {
    if (Auth::guard('admin')->check()) {
        $admin = Auth::guard('admin')->user();
        return [
            'id'   => 'admin-' . $admin->id,
            'name' => $admin->name,
            'role' => 'admin',
        ];
    }

    return false;
});

Broadcast::channel('presence-customers', function () {
    if (Auth::guard('customer')->check()) {
        $customer = Auth::guard('customer')->user();
        return [
            'id'   => 'customer-' . $customer->id,
            'name' => $customer->name,
            'role' => 'customer',
        ];
    }

    return false;
});

Broadcast::channel('customer-monitor', function () {
    $isAdmin = auth('admin')->check();
    \Illuminate\Support\Facades\Log::info('customer-monitor channel auth check', [
        'is_admin' => $isAdmin,
        'admin_id' => auth('admin')->id(),
        'admin_user' => auth('admin')->user() ? auth('admin')->user()->name : 'none'
    ]);
    return $isAdmin;
});
