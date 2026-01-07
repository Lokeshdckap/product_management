<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;




Broadcast::routes([
    'middleware' => ['web', 'broadcast.auth'],
]);

Route::get('/', function () {
    if (auth('customer')->check()) {
        return redirect()->route('home');
    }

    if (auth('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
});



// Route::get('/generate-csv', function () {
//     $filePath = storage_path('app/products_100k.csv');

//     $categories = ["Mobiles","Laptops","Electronics","Home Appliances","Furniture","Accessories","Wearables","Footwear","Cameras","Gaming","Kitchen","Bags","Beauty","Personal Care","Fitness"];
    
//     $file = fopen($filePath, 'w');
//     fputcsv($file, ['name','description','price','stock','category','image']);

//     for ($i = 1; $i <= 100000; $i++) {
//         $name = "Product $i";
//         $desc = "Description for product $i";
//         $price = rand(100,100000);
//         $stock = rand(1,500);
//         $category = $categories[array_rand($categories)];
//         $image = ''; // test default image
//         fputcsv($file, [$name,$desc,$price,$stock,$category,$image]);
//     }

//     fclose($file);

//     return "100k CSV generated at: $filePath";
// });


// Route::get('/', function () {
//     phpinfo();
// });


// Route::get('/debug-auth', function () {
//     return response()->json([
//         'admin_authenticated' => auth('admin')->check(),
//         'admin_user' => auth('admin')->user(),
//         'customer_authenticated' => auth('customer')->check(),
//         'session' => session()->all(),
//         'guards' => array_keys(config('auth.guards')),
//     ]);
// })->middleware(['web']);

// Route::post('/broadcasting/auth', function (Request $request) {
//     if (auth('admin')->check()) {
//         return Broadcast::auth($request);
//     } elseif (auth('customer')->check()) {
//         return Broadcast::auth($request);
//     }
    
//     return response()->json(['message' => 'Unauthenticated'], 403);
// })->middleware(['web']);


// Route::middleware(['web', 'auth:admin'])->get('/test-auth', function () {
//     return response()->json([
//         'authenticated' => true,
//         'admin' => auth('admin')->user(),
//         'session' => session()->all()
//     ]);
// });


// Route::post('/heartbeat', function () {

//     if (auth('admin')->check()) {
//         auth('admin')->user()->update([
//             'is_online'   => true,
//             'last_seen_at'=> now(),
//         ]);
//     }

//     if (auth('customer')->check()) {
//         auth('customer')->user()->update([
//             'is_online'   => true,
//             'last_seen_at'=> now(),
//         ]);
//     }

//     return response()->json(['status' => 'ok']);
// });







