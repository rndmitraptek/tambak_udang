<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManajemenTambak\TransaksiBiayaController;
use App\Http\Controllers\ManajemenTambak\TransaksiBiayaSimulasiController;

Route::prefix('transaksi-biaya')->group(function() {
    Route::get('/', [TransaksiBiayaController::class, 'index'])->name('transaksi-biaya.index');
    Route::get('/data', [TransaksiBiayaController::class, 'data'])->name('transaksi-biaya.data'); // datatables
    Route::get('/biaya-list', [TransaksiBiayaController::class, 'biayaList'])->name('transaksi-biaya.biaya-list');
    Route::get('/petak-list', [TransaksiBiayaController::class, 'petakList'])->name('transaksi-biaya.petak-list');
    Route::post('/store', [TransaksiBiayaController::class, 'store'])->name('transaksi-biaya.store');
    Route::get('/{uuid}', [TransaksiBiayaController::class, 'show'])->name('transaksi-biaya.show');
    Route::post('/update/{uuid}', [TransaksiBiayaController::class, 'update'])->name('transaksi-biaya.update');
    Route::post('/validasi/{uuid}', [TransaksiBiayaController::class, 'action_validasi'])->name('transaksi-biaya.validasi');
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

Route::prefix('simulasi')->name('simulasi.')->group(function(){
    Route::get('/',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'index'])->name('index');
    Route::post('/store',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'store'])->name('store');
    Route::post('/update/{uuid}',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'update'])->name('update');
    Route::get('/data',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'data'])->name('data');
    Route::delete('/delete/{uuid}',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'destroy'])->name('delete');
    Route::get('/lokasi',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'lokasi'])->name('lokasi');
    Route::get('/siklus/{lokasiId}', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'siklusByLokasi'])->name('siklusByLokasi');
    Route::get('/petak/{siklusId}/{tanggalSimulasi}', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'petakBySiklus'])->name('petakBySiklus');
    Route::get('/get-biaya-simulasi/{simulasiId}', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'getBiayaSimulasi'])->name('getBiayaSimulasi');
    Route::get('/show/{uuid}', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'show'])->name('show');
    Route::get('/form_biaya_simulasi/{uuid_simulasi}', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'form_biaya_simulasi'])->name('form_biaya_simulasi');
    Route::post('/pendapatan/save', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'pendapatan_save'])->name('pendapatan_save');
    Route::get('/get_blok/{uuid_lokasi}',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'get_blok'])->name('get_blok');
    Route::get('/get_petak/{uuid_blok}',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'get_petak'])->name('get_petak');
    Route::get('/get_detail/{uuid}',[App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'get_detail'])->name('get_detail');
});

Route::prefix('transaksi-biaya-simulasi')->group(function() {
    Route::get('/', [TransaksiBiayaSimulasiController::class, 'index'])->name('transaksi-biaya-simulasi.index');
    Route::get('/data', [TransaksiBiayaSimulasiController::class, 'data'])->name('transaksi-biaya-simulasi.data'); // datatables
    Route::get('/biaya-list', [TransaksiBiayaSimulasiController::class, 'biayaList'])->name('transaksi-biaya-simulasi.biaya-list');
    Route::get('/petak-list', [TransaksiBiayaSimulasiController::class, 'petakList'])->name('transaksi-biaya-simulasi.petak-list');
    Route::post('/store', [TransaksiBiayaSimulasiController::class, 'store'])->name('transaksi-biaya-simulasi.store');
    Route::get('/{uuid}', [TransaksiBiayaSimulasiController::class, 'show'])->name('transaksi-biaya-simulasi.show');
    Route::post('/update/{uuid}', [TransaksiBiayaSimulasiController::class, 'update'])->name('transaksi-biaya-simulasi.update');
    Route::post('/validasi/{uuid}', [TransaksiBiayaSimulasiController::class, 'action_validasi'])->name('transaksi-biaya-simulasi.validasi');
    Route::delete('/{uuid}', [TransaksiBiayaSimulasiController::class, 'destroy'])->name('transaksi-biaya-simulasi.destroy');
});