<?php

use App\Http\Controllers\Customer\DashboardController;

Route::middleware('auth:customer')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('customer.dashboard');
});
