<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\RegistrationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ImportController;



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

    Route::post('products/import', [ProductController::class, 'import'])
    ->name('products.import');

    Route::get('/imports', ImportController::class);

    Route::get('/imports/{import}/failed-download',[ImportController::class, 'downloadFailed'])->name('admin.imports.failed.download');

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
