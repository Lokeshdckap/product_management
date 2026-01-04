<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\Admin;

class RegistrationController extends Controller
{
   public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = new Admin();
        $admin->uuid = Str::uuid();
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = Hash::make($request->input('password'));

        $admin->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Admin created successfully');
    } 
}