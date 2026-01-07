<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\RegistrationController;

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes (Guest)
|--------------------------------------------------------------------------
*/

Route::middleware('guest:customer')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.check');

    Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/register', [RegistrationController::class, 'register'])
        ->name('register.store');
});


/*
|--------------------------------------------------------------------------
| Customer Protected Routes (Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->group(function () {

    Route::get('/home', HomeController::class)
        ->name('home');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});
