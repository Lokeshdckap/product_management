<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegistrationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;



Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('admin.login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])
        ->name('admin.register');
    Route::post('/register', [RegistrationController::class, 'register'])->name('admin.store');

    // Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

// });

    // Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/prodi', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::post('/logout', [LoginController::class, 'logout'])
            ->name('admin.logout');
    // });
});
