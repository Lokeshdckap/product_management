<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\CustomerPresenceChange;
use Illuminate\Support\Facades\Log;

class PresenceController extends Controller
{
    public function joined()
    {
        $customer = auth('customer')->user();
        
        if ($customer) {

            $customer->update(['is_online' => true, 'last_seen_at' => now()]);

            broadcast(new CustomerPresenceChange(
                ['id' => $customer->id, 'name' => $customer->name],
                'joined'
            ));
        } 
        else {
            dump(`No authenticated customer found`);
        }

        return response()->json(['ok' => true]);
    }

    public function left()
    {
        $customer = auth('customer')->user();
        
        if ($customer) {

            $customer->update(['is_online' => false, 'last_seen_at' => now()]);
            
            broadcast(new CustomerPresenceChange(
                ['id' => $customer->id, 'name' => $customer->name],
                'left'
            ));
        } 
        else {
            dump(`No authenticated customer found`);
        }

        return response()->json(['ok' => true]);
    }
}
