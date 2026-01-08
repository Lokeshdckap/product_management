<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Admin;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view("admin.auth.register");
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "email", "max:255", "unique:admins,email"],
            "password" => ["required", "string", "min:6", "confirmed"],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        Auth::guard("admin")->login($admin);

        return redirect()
            ->route("admin.dashboard")
            ->with("success", "Admin registered and logged in successfully");
    }
}
