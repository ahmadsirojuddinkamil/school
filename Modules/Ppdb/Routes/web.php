<?php

use Illuminate\Support\Facades\Route;
use Modules\Ppdb\Http\Controllers\PpdbController;

Route::controller(PpdbController::class)->group(function () {
    Route::get('/ppdb', 'register')->name('ppdb.register');
    Route::post('/ppdb', 'store')->name('ppdb.store');

    Route::middleware('auth')->group(function () {
        Route::group(['middleware' => ['role:admin']], function () {
            Route::get('/ppdb-data/year', 'year')->name('ppdb.year');
            Route::get('/ppdb-data/year/{save_year_from_event}', 'showYear')->name('ppdb.year.show');
            Route::post('/ppdb-data/download/pdf/{save_year_from_event}', 'downloadPdf')->name('ppdb.download.pdf');
            Route::post('/ppdb-data/download/excel/{save_year_from_event}', 'downloadExcel')->name('ppdb.download.excel');
            Route::get('/ppdb-data/{save_uuid_from_event}', 'show')->name('ppdb.user.show');
            Route::post('/ppdb-data/{save_uuid_from_event}', 'accept')->name('ppdb.accept');
            Route::get('/ppdb-data/{save_uuid_from_event}/edit', 'edit')->name('ppdb.show.edit');
            Route::put('/ppdb-data/{save_uuid_from_event}', 'update')->name('ppdb.update');
            Route::delete('/ppdb-data/{save_uuid_from_event}', 'delete')->name('ppdb.delete');
        });
    });
});
