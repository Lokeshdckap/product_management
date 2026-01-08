<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view("admin.dashboard", [
            "admins" => Admin::select("id", "name")->get(),
            "customers" => Customer::select("id", "name")->get(),
        ]);
    }
}
