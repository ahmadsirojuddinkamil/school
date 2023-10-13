<?php

use Illuminate\Support\Facades\Route;
use Modules\Ppdb\Http\Controllers\PpdbController;

Route::controller(PpdbController::class)->group(function () {
    Route::middleware('open_or_close_ppdb')->group(function () {
        Route::get('/ppdb', 'register')->name('ppdb.register');
        Route::post('/ppdb', 'store')->name('ppdb.store');
    });

    Route::middleware('auth')->group(function () {
        Route::group(['middleware' => ['role:admin']], function () {
            Route::get('/data-ppdb/tahun-daftar', 'year')->name('ppdb.year');
            Route::post('/data-ppdb/open', 'openPpdb')->name('ppdb.open');
            Route::delete('/data-ppdb/status', 'deleteOpenPpdb')->name('ppdb.deleteOpenPpdb');
            Route::get('/data-ppdb/tahun-daftar/{save_year_from_event}', 'showYear')->name('ppdb.year.show');
            Route::post('/data-ppdb/download/pdf/{save_year_from_event}', 'downloadPdf')->name('ppdb.download.pdf');
            Route::post('/data-ppdb/download/excel/{save_year_from_event}', 'downloadExcel')->name('ppdb.download.excel');
            Route::get('/data-ppdb/{save_uuid_from_event}', 'show')->name('ppdb.user.show');
            Route::post('/data-ppdb/{save_uuid_from_event}/accept', 'accept')->name('ppdb.accept');
            Route::get('/data-ppdb/{save_uuid_from_event}/edit', 'edit')->name('ppdb.show.edit');
            Route::put('/data-ppdb/{save_uuid_from_event}', 'update')->name('ppdb.update');
            Route::delete('/data-ppdb/{save_uuid_from_event}', 'delete')->name('ppdb.delete');
        });
    });
});
