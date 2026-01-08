<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;


class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $products = Product::query()
            ->latest('created_at')
            ->paginate(12);

        return view('customer.home', compact('products'));
    }
}
