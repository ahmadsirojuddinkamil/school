<?php

use Illuminate\Support\Facades\Route;
use Modules\MataPelajaran\Http\Controllers\MataPelajaranController;

Route::controller(MataPelajaranController::class)->middleware('auth')->group(function () {
    Route::group(['middleware' => ['role:admin|super_admin']], function () {
        Route::get('/data-mata-pelajaran', 'list')->name('data.mata.pelajaran');
        Route::get('/data-mata-pelajaran/create', 'create')->name('create.mata.pelajaran');
        Route::post('/data-mata-pelajaran/store', 'store')->name('store.mata.pelajaran');
        Route::get('/data-mata-pelajaran/download-all-data/{save_name_from_event}', 'downloadAllMapel')->name('download.all.mata.pelajaran');
        Route::get('/data-mata-pelajaran/{save_uuid_from_event}', 'show')->name('show.mata.pelajaran');
        Route::get('/data-mata-pelajaran/{save_uuid_from_event}/edit', 'edit')->name('edit.mata.pelajaran');
        Route::put('/data-mata-pelajaran/{save_uuid_from_event}/update', 'update')->name('update.mata.pelajaran');
    });

    Route::group(['middleware' => ['role:admin|super_admin|guru|siswa']], function () {
        Route::get('/data-mata-pelajaran/{save_name_from_event}/data/materi/{save_uuid_from_event}', 'downloadDataMapel')->name('download.data.mata.pelajaran');
        Route::get('/data-mata-pelajaran/{save_uuid_from_event}/materi/pdf', 'downloadMateriPdf')->name('download.pdf.mata.pelajaran');
        Route::get('/data-mata-pelajaran/{save_uuid_from_event}/materi/ppt', 'downloadMateriPpt')->name('download.ppt.mata.pelajaran');
    });

    Route::group(['middleware' => ['role:admin|super_admin']], function () {
        Route::delete('/data-mata-pelajaran/{save_uuid_from_event}/delete', 'delete')->name('delete.mata.pelajaran');
    });
});
