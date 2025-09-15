<?php
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('po')->name('po.')->group(function(){
        Route::post('/insert',[App\Http\Controllers\Finance\PoController::class, 'insert'])->name('insert');
        Route::post('/update/{uuid}',[App\Http\Controllers\Finance\PoController::class, 'update'])->name('update');
        Route::get('/datatable',[App\Http\Controllers\Finance\PoController::class, 'datatable'])->name('datatable');
        Route::delete('/delete/{uuid}',[App\Http\Controllers\Finance\PoController::class, 'destroy'])->name('delete');
        Route::get('/supplier',[App\Http\Controllers\Finance\PoController::class, 'supplier'])->name('supplier');
        Route::get('/lokasi',[App\Http\Controllers\Finance\PoController::class, 'lokasi'])->name('lokasi');
    });
});