<?php

use Illuminate\Support\Facades\Route;
use Modules\Absen\Http\Controllers\AbsenController;
use Modules\Absen\Http\Controllers\AbsenGuruController;
use Modules\Absen\Http\Controllers\AbsenSiswaController;

Route::controller(AbsenController::class)->middleware('auth')->group(function () {
    Route::group(['middleware' => ['role:siswa|guru']], function () {
        Route::get('/absen', 'absen')->name('page.absen');
        Route::post('/absen', 'store')->name('page.absen.store');
        Route::get('/laporan-absen', 'laporan')->name('laporan.absen');
        Route::post('/laporan-absen/pdf', 'userDownloadPdfLaporanAbsen')->name('laporan.absen.pdf');
    });
});

Route::controller(AbsenSiswaController::class)->middleware('auth')->group(function () {
    Route::group(['middleware' => ['role:admin|super_admin']], function () {
        Route::get('/data-absen/siswa', 'getListClass')->name('data.absen');
        Route::get('/data-absen/siswa/{save_class_from_event}', 'showClass')->name('data.absen.class');
        Route::get('/data-absen/siswa/{save_uuid_from_event}/show', 'showDataAbsen')->name('data.absen.show');

        Route::get('/laporan-absen/siswa/{save_uuid_from_event}/pdf/admin', 'adminDownloadPdfLaporanAbsen')->name('laporan.absen.pdf.admin');
        Route::get('/laporan-absen/siswa/{save_uuid_from_event}/excel/admin', 'adminDownloadExcelLaporanAbsen')->name('laporan.absen.excel.admin');

        Route::get('/data-absen/siswa/{save_class_from_event}/download/pdf/zip', 'downloadZipLaporanAbsenSiswaPdf')->name('data.absen.siswa.download.pdf.zip');
        Route::get('/data-absen/siswa/{save_class_from_event}/download/excel/zip', 'downloadZipLaporanAbsenSiswaExcel')->name('data.absen.siswa.download.excel.zip');
        Route::get('/data-absen/siswa/{save_uuid_from_event}/{save_date_from_event}/edit', 'editTanggalAbsenSiswa')->name('data.tanggal.absen.edit');
        Route::put('/data-absen/siswa/date/{save_uuid_from_event}', 'updateTanggalAbsenSiswa')->name('data.tanggal.absen.update');
    });

    Route::group(['middleware' => ['role:super_admin']], function () {
        Route::delete('/data-absen/siswa/report', 'deleteLaporanAbsenSiswa')->name('data.absen.delete');
        Route::delete('/data-absen/siswa/tanggal', 'deleteTanggalAbsenSiswa')->name('data.tanggal.absen.delete');
    });
});

Route::controller(AbsenGuruController::class)->middleware('auth')->group(function () {
    Route::group(['middleware' => ['role:admin|super_admin']], function () {
        Route::get('/data-absen/guru', 'listAbsen')->name('data.absen.guru');
        Route::delete('/data-absen/guru', 'deleteAbsen')->name('data.absen.guru.delete');
        Route::get('/data-absen/guru/download/pdf/zip', 'downloadZipAbsenGuruPdf')->name('data.absen.guru.download.pdf.zip');
        Route::get('/data-absen/guru/download/excel/zip', 'downloadZipAbsenGuruExcel')->name('data.absen.guru.download.excel.zip');
        Route::get('/data-absen/guru/{save_uuid_from_event}', 'dataAbsen')->name('data.absen.guru.show');
        Route::get('/data-absen/guru/{save_uuid_from_event}/download/pdf', 'downloadPdfAbsenGuru')->name('data.absen.guru.download.pdf');
        Route::get('/data-absen/guru/{save_uuid_from_event}/download/excel', 'downloadExcelAbsenGuru')->name('data.absen.guru.download.excel');
        Route::get('/data-absen/guru/{save_uuid_from_event}/{save_date_from_event}/edit', 'editAbsen')->name('data.absen.guru.date.edit');
        Route::put('/data-absen/guru/date/{save_uuid_from_event}', 'updateDateAbsen')->name('data.absen.guru.date.update');
    });

    Route::group(['middleware' => ['role:super_admin']], function () {
        Route::delete('/data-absen/guru/date', 'deleteDateAbsen')->name('data.absen.guru.date.delete');
    });
});
