<?php

use Illuminate\Support\Facades\Route;
use Modules\Siswa\Http\Controllers\SiswaController;

Route::controller(SiswaController::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::group(['middleware' => ['role:admin']], function () {
            Route::get('/siswa-data/status/aktif/kelas', 'getListClassSiswa')->name('siswa.active');
            Route::get('/siswa-data/status/aktif/kelas/{save_class_from_event}', 'showSiswaClass')->name('siswa.active.class');
            Route::get('/siswa-data/status/aktif/kelas/{save_class_from_event}/{save_uuid_from_event}', 'showSiswaActive')->name('show.siswa.active');
            Route::post('/siswa-data/aktif/download/pdf', 'downloadPdfSiswaActive')->name('siswa.active.download.pdf');
            Route::post('/siswa-data/aktif/download/excel', 'downloadExcelSiswaActive')->name('siswa.active.download.excel');

            Route::get('/siswa-data/status', 'getStatus')->name('siswa.status');
            Route::get('/siswa-data/status/sudah-lulus', 'getListSiswaGraduated')->name('siswa.graduated');
            Route::get('/siswa-data/{save_uuid_from_event}/status/sudah-lulus', 'showSiswaGraduated')->name('siswa.graduated.show');
            Route::post('/siswa-data/graduated/download/pdf', 'downloadPdfSiswaGraduated')->name('siswa.graduated.download.pdf');
            Route::post('/siswa-data/graduated/download/excel', 'downloadExcelSiswaGraduated')->name('siswa.download.excel.graduated');
            Route::get('/siswa-data/{save_uuid_from_event}/edit', 'edit')->name('siswa.edit');
            Route::put('/siswa-data/{save_uuid_from_event}', 'update')->name('siswa.update');
            Route::delete('/siswa-data/{save_uuid_from_event}', 'delete')->name('siswa.delete');
        });
    });
});
