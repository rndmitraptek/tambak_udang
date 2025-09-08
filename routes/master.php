<?php

use Illuminate\Support\Facades\Route;

// GROUP SETUP LOKASI
Route::prefix('lokasi')->name('lokasi.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\LokasiController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\LokasiController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\LokasiController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'destroy'])->name('delete');
});

// GROUP SETUP BLOK
Route::prefix('blok')->name('blok.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\BlokController::class, 'index'])->name('index');
    Route::get('/lokasi-list', [App\Http\Controllers\Master\BlokController::class, 'lokasiList'])->name('lokasi-list');
    Route::get('/data', [App\Http\Controllers\Master\BlokController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\BlokController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'destroy'])->name('delete');
});

// GROUP SETUP PETAK
Route::prefix('petak')->name('petak.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\PetakController::class, 'index'])->name('index');
    Route::get('/lokasi-list', [App\Http\Controllers\Master\PetakController::class, 'lokasiList'])->name('lokasi-list');
    Route::get('/blok-list-by-lokasi/{lokasi_id}', [App\Http\Controllers\Master\PetakController::class, 'blokListByLokasi'])->name('blok-list-by-lokasi');
    Route::get('/blok-list', [App\Http\Controllers\Master\PetakController::class, 'blokList'])->name('blok-list');
    Route::get('/data', [App\Http\Controllers\Master\PetakController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\PetakController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\PetakController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\PetakController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\PetakController::class, 'destroy'])->name('delete');
});

// GROUP SETUP BENUR
Route::prefix('benur')->name('benur.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\BenurController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\BenurController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\BenurController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\BenurController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\BenurController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\BenurController::class, 'destroy'])->name('delete');
});

// GROUP SETUP PAKAN
Route::prefix('pakan')->name('pakan.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\PakanController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\PakanController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\PakanController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\PakanController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\PakanController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\PakanController::class, 'destroy'])->name('delete');
});