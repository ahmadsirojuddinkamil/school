<?php

use App\Http\Controllers\PpdbController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.news.index');
});

Route::controller(PpdbController::class)->group(function () {
    Route::get('/ppdb', 'index');
    Route::post('/ppdb', 'store');
});
