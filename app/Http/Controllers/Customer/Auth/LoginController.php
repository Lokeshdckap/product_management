<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view("customer.auth.login");
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        if (Auth::guard("customer")->attempt($credentials)) {
            return redirect()->route("home");
        }

        return back()->withErrors(["email" => "Invalid credentials"]);
    }

    public function logout()
    {
        Auth::guard("customer")->logout();
        return redirect()->route("customer.login");
    }
}
