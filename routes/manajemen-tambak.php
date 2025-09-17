<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManajemenTambak\TransaksiBiayaController;

Route::prefix('transaksi-biaya')->group(function() {
    Route::get('/', [TransaksiBiayaController::class, 'index'])->name('transaksi-biaya.index');
    Route::get('/data', [TransaksiBiayaController::class, 'data'])->name('transaksi-biaya.data'); // datatables
    Route::get('/biaya-list', [TransaksiBiayaController::class, 'biayaList'])->name('transaksi-biaya.biaya-list');
    Route::get('/petak-list', [TransaksiBiayaController::class, 'petakList'])->name('transaksi-biaya.petak-list');
    Route::post('/store', [TransaksiBiayaController::class, 'store'])->name('transaksi-biaya.store');
    Route::get('/{uuid}', [TransaksiBiayaController::class, 'show'])->name('transaksi-biaya.show');
    Route::post('/update/{uuid}', [TransaksiBiayaController::class, 'update'])->name('transaksi-biaya.update');
    Route::delete('/{uuid}', [TransaksiBiayaController::class, 'destroy'])->name('transaksi-biaya.destroy');
});

Route::prefix('panen')->name('panen.')->group(function(){
    Route::post('/insert',[App\Http\Controllers\ManajemenTambak\PanenController::class, 'insert'])->name('insert');
    Route::post('/update/{uuid}',[App\Http\Controllers\ManajemenTambak\PanenController::class, 'update'])->name('update');
    Route::get('/datatable',[App\Http\Controllers\ManajemenTambak\PanenController::class, 'datatable'])->name('datatable');
    Route::delete('/delete/{uuid}',[App\Http\Controllers\ManajemenTambak\PanenController::class, 'destroy'])->name('delete');
    Route::get('/get_siklus',[App\Http\Controllers\ManajemenTambak\PanenController::class, 'get_siklus'])->name('get_siklus');
    Route::get('/get_blok/{uuid_lokasi}',[App\Http\Controllers\ManajemenTambak\PanenController::class, 'get_blok'])->name('get_blok');
    Route::get('/get_petak/{uuid_blok}',[App\Http\Controllers\ManajemenTambak\PanenController::class, 'get_petak'])->name('get_petak');
    Route::get('/get_detail/{uuid}',[App\Http\Controllers\ManajemenTambak\PanenController::class, 'get_detail'])->name('get_detail');
});