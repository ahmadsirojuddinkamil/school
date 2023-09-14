<?php

use Illuminate\Support\Facades\Route;
use Modules\Siswa\Http\Controllers\SiswaController;

Route::controller(SiswaController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::group(['middleware' => ['role:admin']], function () {
            Route::get('/siswa-data/class', 'class')->name('siswa.class');
            Route::get('/siswa-data/class/{save_class_from_event}', 'showClass')->name('siswa.class.show');
            Route::post('/siswa-data/download/pdf/{save_date_from_event}', 'downloadPdf')->name('siswa.download.pdf');
            Route::post('/siswa-data/download/excel/{save_date_from_event}', 'downloadExcel')->name('siswa.download.excel');
            Route::get('/siswa-data/{save_uuid_from_event}', 'show')->name('siswa.user.show');
            Route::post('/siswa-data/{save_uuid_from_event}', 'accept')->name('siswa.accept');
            Route::get('/siswa-data/{save_uuid_from_event}/edit', 'edit')->name('siswa.show.edit');
            Route::put('/siswa-data/{save_uuid_from_event}', 'update')->name('siswa.update');
            Route::delete('/siswa-data/{save_uuid_from_event}', 'delete')->name('siswa.delete');
        });
    });
});
