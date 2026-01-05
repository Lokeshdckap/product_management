<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

Route::get('/generate-csv', function () {
    $filePath = storage_path('app/products_100k.csv');

    $categories = ["Mobiles","Laptops","Electronics","Home Appliances","Furniture","Accessories","Wearables","Footwear","Cameras","Gaming","Kitchen","Bags","Beauty","Personal Care","Fitness"];
    
    $file = fopen($filePath, 'w');
    fputcsv($file, ['name','description','price','stock','category','image']);

    for ($i = 1; $i <= 100000; $i++) {
        $name = "Product $i";
        $desc = "Description for product $i";
        $price = rand(100,100000);
        $stock = rand(1,500);
        $category = $categories[array_rand($categories)];
        $image = ''; // test default image
        fputcsv($file, [$name,$desc,$price,$stock,$category,$image]);
    }

    fclose($file);

    return "100k CSV generated at: $filePath";
});


Route::get('/', function () {
    phpinfo();
});





Route::post('/heartbeat', function () {

    if (auth('admin')->check()) {
        auth('admin')->user()->update([
            'is_online'   => true,
            'last_seen_at'=> now(),
        ]);
    }

    if (auth('customer')->check()) {
        auth('customer')->user()->update([
            'is_online'   => true,
            'last_seen_at'=> now(),
        ]);
    }

    return response()->json(['status' => 'ok']);
});







