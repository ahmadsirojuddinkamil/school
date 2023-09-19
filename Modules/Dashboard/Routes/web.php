<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index');
})->middleware(['auth', 'verified']);
