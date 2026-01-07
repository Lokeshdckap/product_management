<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegistrationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ImportController;

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/


Route::prefix('admin')
    ->name('admin.')
    ->middleware('guest:admin')
    ->group(function () {

        Route::get('/login', [LoginController::class, 'showLoginForm'])
            ->name('login');

        Route::post('/login', [LoginController::class, 'login'])
            ->name('loginCheck');

        Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])
            ->name('register');

        Route::post('/register', [RegistrationController::class, 'register'])
            ->name('store');
    });


/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('products', ProductController::class);
        
        Route::resource('categories', CategoryController::class);

        Route::post('products/import', [ProductController::class, 'import'])
            ->name('products.import');

        Route::get('/imports', ImportController::class);
        Route::get('/imports/{import}/failed-download', [ImportController::class, 'downloadFailed'])
            ->name('imports.failed.download');

        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('logout');
    });
