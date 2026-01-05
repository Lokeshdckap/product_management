<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use App\Models\Customer;

class UpdateUserOnlineStatus extends Command
{
    protected $signature = 'users:update-online-status';

    protected $description = 'Mark users offline if inactive';

    public function handle()
    {
        Admin::where('last_seen_at', '<', now()->subMinutes(2))
            ->update(['is_online' => false]);

        Customer::where('last_seen_at', '<', now()->subMinutes(2))
            ->update(['is_online' => false]);

        $this->info('User online status updated.');
    }
}
