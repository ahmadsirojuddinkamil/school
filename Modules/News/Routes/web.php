<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('news::pages.news.index');
})->name('news');
