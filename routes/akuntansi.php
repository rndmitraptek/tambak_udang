<?php

use App\Models\ManajemenTambak\penaburanBenurModel;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('jurnal')->name('jurnal.')->group(function(){
        Route::post('/insert',[App\Http\Controllers\Akuntansi\JurnalController::class, 'insert'])->name('insert');
        Route::post('/update/{uuid}',[App\Http\Controllers\Akuntansi\JurnalController::class, 'update'])->name('update');
        Route::get('/get_coa',[App\Http\Controllers\Akuntansi\JurnalController::class, 'get_coa'])->name('get_coa');
        Route::post('/jurnal_umum',[App\Http\Controllers\Akuntansi\JurnalController::class, 'jurnal_umum'])->name('jurnal_umum');
        Route::post('/get_buku_besar',[App\Http\Controllers\Akuntansi\JurnalController::class, 'get_buku_besar'])->name('get_buku_besar');
        Route::post('/get_laba_rugi',[App\Http\Controllers\Akuntansi\JurnalController::class, 'get_laba_rugi'])->name('get_laba_rugi');
        Route::post('/get_neraca',[App\Http\Controllers\Akuntansi\JurnalController::class, 'get_neraca'])->name('get_neraca');
        Route::get('/datatable',[App\Http\Controllers\Akuntansi\JurnalController::class, 'datatable'])->name('datatable');
        Route::get('/get_detail/{uuid}',[App\Http\Controllers\Akuntansi\JurnalController::class, 'get_detail'])->name('get_detail');

    });
    Route::prefix('laba_rugi_petak')->name('laba_rugi_petak.')->group(function(){
        Route::get('/lokasi',[App\Http\Controllers\Akuntansi\LabaRugiPetakController::class, 'lokasi'])->name('lokasi');
        Route::get('/siklus/{lokasiId}',[App\Http\Controllers\Akuntansi\LabaRugiPetakController::class, 'siklus'])->name('siklus');
        Route::post('/summary',[App\Http\Controllers\Akuntansi\LabaRugiPetakController::class, 'summary'])->name('summary');
        Route::post('/detail',[App\Http\Controllers\Akuntansi\LabaRugiPetakController::class, 'detail'])->name('detail');
    });
});