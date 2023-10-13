<?php

use Illuminate\Support\Facades\Route;
use Modules\Guru\Http\Controllers\GuruController;

Route::controller(GuruController::class)->group(function () {
    Route::group(['middleware' => ['auth', 'role:admin']], function () {
        Route::get('/data-guru', 'listCourse')->name('data.guru');
    });
});
