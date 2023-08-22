<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.news.index');
});

Route::controller(PpdbController::class)->group(function () {
    Route::get('/ppdb', 'index');
    Route::post('/ppdb', 'store');

    Route::middleware('auth')->group(function () {
        Route::group(['middleware' => ['role:admin']], function () {
            Route::get('/ppdb-data', 'list');
            Route::get('/ppdb-data/{save_uuid_from_event}', 'show');
            Route::post('/ppdb-data/{save_uuid_from_event}', 'accept');
            Route::get('/ppdb-data/{save_uuid_from_event}/edit', 'edit');
            Route::put('/ppdb-data/{save_uuid_from_event}', 'update');
            Route::delete('/ppdb-data/{save_uuid_from_event}', 'delete');
        });
    });
});

Route::controller(DashboardController::class)->group(function () {
    Route::get('/dashboard', 'index');
})->middleware(['auth', 'verified']);;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
