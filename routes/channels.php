<?php

use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('presence.admins', function ($user) {
    if ($user instanceof Admin) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'role' => 'admin',
        ];
    }
});

Broadcast::channel('presence.customers', function ($user) {
    if ($user instanceof Customer) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'role' => 'customer',
        ];
    }
});
