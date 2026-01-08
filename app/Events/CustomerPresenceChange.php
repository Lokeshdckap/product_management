<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\Log;

class CustomerPresenceChange implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;
    public $status;
    public $broadcastQueue = null;

    public function __construct($customer, $status)
    {
        $this->customer = $customer;
        $this->status   = $status;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('customer-monitor');
    }

    public function broadcastAs()
    {
        return 'customer.presence.changed';
    }

    public function broadcastWith()
    {
        return [
            'customer' => $this->customer,
            'status'   => $this->status,
        ];
    }
}
