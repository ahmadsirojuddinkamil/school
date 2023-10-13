<?php

use Illuminate\Support\Facades\Route;
use Modules\Absen\Http\Controllers\AbsenController;

Route::controller(AbsenController::class)->group(function () {
    Route::group(['middleware' => ['auth', 'role:siswa|guru']], function () {
        Route::get('/absen', 'absen')->name('page.absen');
        Route::post('/absen', 'store')->name('page.absen.store');
        Route::get('/laporan-absen', 'laporan')->name('laporan.absen');
    });

    Route::group(['middleware' => ['auth', 'role:siswa|guru|admin']], function () {
        Route::post('/laporan-absen/pdf', 'downloadPdfLaporanAbsen')->name('laporan.absen.pdf');
    });

    Route::group(['middleware' => ['auth', 'role:admin']], function () {
        Route::get('/data-absen', 'getListClass')->name('data.absen');
        Route::get('/data-absen/{save_class_from_event}', 'showClass')->name('data.absen.class');
        Route::get('/data-absen/{save_nisn_from_event}/show', 'showDataSiswa')->name('data.absen.show');
        Route::delete('/data-absen/report', 'deleteLaporanAbsenSiswa')->name('data.absen.delete');
        Route::post('/data-absen/download/zip/class', 'downloadZipLaporanAbsenClass')->name('data.absen.download.zip.class');
        Route::get('/data-absen/{save_uuid_from_event}/{save_date_from_event}/edit', 'editTanggalAbsenSiswa')->name('data.tanggal.absen.edit');
        Route::put('/data-absen/date/{save_uuid_from_event}', 'updateTanggalAbsenSiswa')->name('data.tanggal.absen.update');
        Route::delete('/data-absen/tanggal', 'deleteTanggalAbsenSiswa')->name('data.tanggal.absen.delete');
    });
});
