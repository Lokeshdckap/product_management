<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class RegistrationController extends Controller
{
   public function showRegistrationForm()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $customer = new Customer();
        $customer->uuid = Str::uuid();
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->password = Hash::make($request->input('password'));

        $customer->save();

        Auth::guard('customer')->login($customer);


        return redirect()->route('home')
            ->with('success', 'customer created successfully');
    } 
}