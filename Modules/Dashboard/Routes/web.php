<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;

Route::controller(DashboardController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });
});
