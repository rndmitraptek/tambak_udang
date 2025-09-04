<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});

// SETUP LOKASI
Route::get('/lokasi', [App\Http\Controllers\Master\LokasiController::class, 'index']);
Route::get('/lokasi/data', [App\Http\Controllers\Master\LokasiController::class, 'data']);
Route::post('/lokasi/store', [App\Http\Controllers\Master\LokasiController::class, 'store']);
Route::get('/lokasi/show/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'show']);
Route::post('/lokasi/update/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'update']);
Route::delete('/lokasi/delete/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'destroy']);

// SETUP BLOK
Route::get('/blok', [App\Http\Controllers\Master\BlokController::class, 'index']);
Route::get('/blok/lokasi-list', [App\Http\Controllers\Master\BlokController::class, 'lokasiList']);
Route::get('/blok/data', [App\Http\Controllers\Master\BlokController::class, 'data']);
Route::post('/blok/store', [App\Http\Controllers\Master\BlokController::class, 'store']);
Route::get('/blok/show/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'show']);
Route::post('/blok/update/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'update']);
Route::delete('/blok/delete/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'destroy']);

Route::get('/petak', [App\Http\Controllers\Master\PetakController::class, 'index']);
Route::get('/benur', [App\Http\Controllers\Master\BenurController::class, 'index']);
Route::get('/supplier', [App\Http\Controllers\Master\SupplierController::class, 'index']);
Route::get('/pakan', [App\Http\Controllers\Master\PakanController::class, 'index']);
Route::get('/customer', [App\Http\Controllers\Master\CustomerController::class, 'index']);
Route::get('/coa', [App\Http\Controllers\Master\CoaController::class, 'index']);
ROute::get('/po', [App\Http\Controllers\Finance\PoController::class, 'index']);
ROute::get('/pembelian_pakan', [App\Http\Controllers\Finance\PembelianPakanController::class, 'index']);
route::get('/siklus', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'index']);
route::get('/setup_biaya', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'index']);
Route::get('/transaksi_biaya', [App\Http\Controllers\ManajemenTambak\TransaksiBiayaController::class, 'index']);
Route::get('/panen', [App\Http\Controllers\ManajemenTambak\PanenController::class, 'index']);
// Route::get('/tumbang', [App\Http\Controllers\ManajemenTambak\PanenController::class, 'tumbang']);
Route::get('/penaburan_benur', [App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'index']);
Route::get('/simulasi', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'index']);
Route::get('/transaksi_biaya_validasi', [App\Http\Controllers\ManajemenTambak\TransaksiBiayaController::class, 'validasi']);
Route::get('/menu',[App\Http\Controllers\Auth\MenuController::class, 'index']);
