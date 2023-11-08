<?php

use Illuminate\Support\Facades\Route;
use Modules\MataPelajaran\Http\Controllers\MataPelajaranController;

Route::controller(MataPelajaranController::class)->middleware('auth')->group(function () {
    Route::group(['middleware' => ['role:admin|super_admin']], function () {
        Route::get('/data-mata-pelajaran', 'listMataPelajaran')->name('data.mata.pelajaran');
    });
});
