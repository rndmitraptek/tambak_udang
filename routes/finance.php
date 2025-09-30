<?php

use App\Models\ManajemenTambak\penaburanBenurModel;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('po')->name('po.')->group(function(){
        Route::post('/insert',[App\Http\Controllers\Finance\PoController::class, 'insert'])->name('insert');
        Route::post('/update/{uuid}',[App\Http\Controllers\Finance\PoController::class, 'update'])->name('update');
        Route::get('/datatable',[App\Http\Controllers\Finance\PoController::class, 'datatable'])->name('datatable');
        Route::delete('/delete/{uuid}',[App\Http\Controllers\Finance\PoController::class, 'destroy'])->name('delete');
        Route::get('/supplier',[App\Http\Controllers\Finance\PoController::class, 'supplier'])->name('supplier');
        Route::get('/lokasi',[App\Http\Controllers\Finance\PoController::class, 'lokasi'])->name('lokasi');
        Route::get('/siklus/{id_lokasi}',[App\Http\Controllers\Finance\PoController::class, 'get_siklus'])->name('siklus');
    });
    Route::prefix('penaburan')->name('penaburan.')->group(function(){
        Route::post('/insert',[App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'insert'])->name('insert');
        Route::post('/update/{uuid}',[App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'update'])->name('update');
        Route::get('/datatable',[App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'datatable'])->name('datatable');
        Route::delete('/delete/{uuid}',[App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'destroy'])->name('delete');
        Route::get('/get_po',[App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'get_po'])->name('get_po');
        Route::get('/get_petak/{uuid_siklus}',[App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'get_petak'])->name('get_petak');
        Route::get('/get_benur',[App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'get_benur'])->name('get_benur');
        Route::get('/get_detail/{uuid}',[App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'get_detail'])->name('get_detail');
        
    });
    Route::prefix('pembelian-pakan')->name('pembelian-pakan.')->group(function(){
        Route::post('/insert',[App\Http\Controllers\Finance\PembelianPakanController::class, 'insert'])->name('insert');
        Route::post('/update/{uuid}',[App\Http\Controllers\Finance\PembelianPakanController::class, 'update'])->name('update');
        Route::get('/datatable',[App\Http\Controllers\Finance\PembelianPakanController::class, 'datatable'])->name('datatable');
        Route::delete('/delete/{uuid}',[App\Http\Controllers\Finance\PembelianPakanController::class, 'destroy'])->name('delete');
        Route::get('/get_po',[App\Http\Controllers\Finance\PembelianPakanController::class, 'get_po'])->name('get_po');
        Route::get('/get_supplier',[App\Http\Controllers\Finance\PembelianPakanController::class, 'get_supplier'])->name('get_supplier');
        Route::get('/pakan',[App\Http\Controllers\Finance\PembelianPakanController::class, 'pakan'])->name('pakan');
        Route::get('/lokasi',[App\Http\Controllers\Finance\PembelianPakanController::class, 'lokasi'])->name('lokasi');
        Route::get('/siklus/{id_lokasi}',[App\Http\Controllers\Finance\PembelianPakanController::class, 'get_siklus'])->name('siklus');
        Route::get('/get_petak/{uuid_siklus}',[App\Http\Controllers\Finance\PembelianPakanController::class, 'get_petak'])->name('get_petak');
        Route::get('/get_benur',[App\Http\Controllers\Finance\PembelianPakanController::class, 'get_benur'])->name('get_benur');
        Route::get('/get_detail/{uuid}',[App\Http\Controllers\Finance\PembelianPakanController::class, 'get_detail'])->name('get_detail');
        
    });
});