<?php

use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

Broadcast::routes([
    'middleware' => ['web', 'broadcast.auth'],
]);


Broadcast::channel('presence-admins', function () {
    if ($admin = Auth::guard('admin')->user()) {
        return ['id' => $admin->id, 'name' => $admin->name];
    }
    return false;
});


Broadcast::channel('presence-customers', function () {
    $customer = Auth::guard('customer')->user();
    $admin    = Auth::guard('admin')->user();

    if ($customer) {
        return ['id' => $customer->id, 'name' => $customer->name, 'type' => 'customer'];
    }

    if ($admin) {
        return ['id' => $admin->id, 'name' => $admin->name, 'type' => 'admin-view'];
    }

    return false;
});


