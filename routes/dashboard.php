<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard.')->group(function(){
    Route::get('/siklus',[App\Http\Controllers\DashboardController::class, 'siklus'])->name('siklus');
    Route::get('/siklus_petak/{uuid_siklus}',[App\Http\Controllers\DashboardController::class, 'siklus_petak'])->name('siklus_petak');
});