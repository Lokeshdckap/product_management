<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\RegistrationController;
/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/


Route::prefix('customer')->name('customer.')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])->name("loginCheck");

    Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/register', [RegistrationController::class, 'register'])
        ->name('store');
});

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('auth:customer')
        ->name('dashboard');
});

