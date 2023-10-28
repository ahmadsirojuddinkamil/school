<?php

use Illuminate\Support\Facades\Route;
use Modules\Siswa\Http\Controllers\SiswaController;

Route::controller(SiswaController::class)->middleware('auth')->group(function () {
    Route::group(['middleware' => ['role:admin|super_admin']], function () {
        Route::get('/data-siswa/status/aktif/kelas', 'listClass')->name('siswa.active');
        Route::get('/data-siswa/status/aktif/kelas/{save_class_from_event}', 'showClass')->name('siswa.active.class');

        Route::get('/data-siswa/aktif/{save_class_from_event}/download/pdf/zip', 'downloadZipSiswaActivePdf')->name('siswa.active.download.zip.pdf');
        Route::get('/data-siswa/aktif/{save_class_from_event}/download/excel', 'downloadExcelSiswaActive')->name('siswa.active.download.excel');
        Route::get('/data-siswa/aktif/{save_class_from_event}/download/excel/zip', 'downloadZipSiswaActiveExcel')->name('siswa.active.download.excel.zip');

        Route::get('/data-siswa/status', 'status')->name('siswa.status');
        Route::get('/data-siswa/status/sudah-lulus', 'siswaGraduated')->name('siswa.graduated');
        Route::get('/data-siswa/{save_uuid_from_event}/status/sudah-lulus', 'showSiswaGraduated')->name('siswa.graduated.show');

        Route::get('/data-siswa/graduated/{save_year_from_event}/download/pdf', 'downloadPdfSiswaGraduated')->name('siswa.graduated.download.pdf');
        Route::get('/data-siswa/graduated/{save_year_from_event}/download/pdf/zip', 'downloadZipSiswaGraduatedPdf')->name('siswa.graduated.download.pdf.zip');

        Route::get('/data-siswa/graduated/{save_year_from_event}/download/excel', 'downloadExcelSiswaGraduated')->name('siswa.graduated.download.excel');
        Route::get('/data-siswa/graduated/{save_year_from_event}/download/excel/zip', 'downloadZipSiswaGraduatedExcel')->name('siswa.graduated.download.excel.zip');
    });

    Route::group(['middleware' => ['role:admin|super_admin|siswa']], function () {
        Route::get('/data-siswa/status/aktif/kelas/{save_class_from_event}/{save_uuid_from_event}', 'siswaActive')->name('show.siswa.active');
        Route::get('/data-siswa/aktif/{save_class_from_event}/download/pdf', 'downloadPdfSiswaActive')->name('siswa.active.download.pdf');
        Route::get('/data-siswa/{save_uuid_from_event}/edit', 'edit')->name('siswa.edit');
        Route::put('/data-siswa/{save_uuid_from_event}', 'update')->name('siswa.update');
    });

    Route::group(['middleware' => ['role:super_admin']], function () {
        Route::delete('/data-siswa/{save_uuid_from_event}', 'delete')->name('siswa.delete');
    });
});
