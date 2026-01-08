<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\PresenceController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\RegistrationController;


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




Route::middleware('auth:customer')->group(function () {

    Route::get('/home', HomeController::class)
        ->name('home');

    Route::post('/presence/customer-joined', [PresenceController::class, 'joined']);
    
    Route::post('/presence/customer-left', [PresenceController::class, 'left']);

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});


