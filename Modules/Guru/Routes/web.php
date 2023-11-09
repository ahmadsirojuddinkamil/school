<?php

use Illuminate\Support\Facades\Route;
use Modules\Guru\Http\Controllers\GuruController;

Route::controller(GuruController::class)->middleware('auth')->group(function () {
    Route::group(['middleware' => ['role:admin|super_admin']], function () {
        Route::get('/data-guru', 'listGuru')->name('data.guru');
        Route::get('/data-guru/download/zip/pdf', 'downloadZipListGuruPdf')->name('data.guru.download.list.pdf.zip');

        Route::get('/data-guru/{save_uuid_from_event}/download/excel', 'downloadExcelBiodataGuru')->name('data.guru.download.excel');
        Route::get('/data-guru/download/excel/zip', 'downloadZipListGuruExcel')->name('data.guru.download.list.excel.zip');

        Route::get('/data-guru/{save_uuid_from_event}/edit/teaching-hours', 'edit')->name('data.guru.edit.teaching.hours');
        Route::put('/data-guru/{save_uuid_from_event}/edit/teaching-hours', 'updateTeachingHours')->name('data.guru.update.teaching.hours');
    });

    Route::group(['middleware' => ['role:super_admin']], function () {
        Route::get('/data-guru/create', 'create')->name('data.guru.create');
        Route::post('/data-guru/create', 'store')->name('data.guru.store');
        Route::delete('/data-guru/{save_uuid_from_event}', 'delete')->name('data.guru.delete');
    });

    Route::group(['middleware' => ['role:super_admin|admin|guru']], function () {
        Route::get('/data-guru/{save_uuid_from_event}', 'show')->name('data.guru.show');
        Route::get('/data-guru/{save_uuid_from_event}/download/pdf', 'downloadPdfBiodataGuru')->name('data.guru.download.pdf');
        Route::get('/data-guru/{save_uuid_from_event}/edit', 'edit')->name('data.guru.edit');
        Route::put('/data-guru/{save_uuid_from_event}/edit', 'update')->name('data.guru.update');
    });
});
