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
});