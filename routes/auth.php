<?php

use App\Http\Controllers\Auth\MenuController;
use Illuminate\Support\Facades\Route;

Route::prefix('menu')->name('menu.')->group(function(){
    Route::post('/insert',[App\Http\Controllers\Auth\MenuController::class, 'insert'])->name('insert');
    Route::post('/update/{uuid}',[App\Http\Controllers\Auth\MenuController::class, 'update'])->name('update');
    Route::get('/datatable',[App\Http\Controllers\Auth\MenuController::class, 'datatable'])->name('datatable');
});