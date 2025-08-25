<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});

Route::get('/lokasi', [App\Http\Controllers\Master\LokasiController::class, 'index']);
