<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout');
});

Route::get('/lokasi', [App\Http\Controllers\Master\LokasiController::class, 'index']);
Route::get('/kelompok_kolam', [App\Http\Controllers\Master\GroupKolamController::class, 'index']);
Route::get('/kolam', [App\Http\Controllers\Master\KolamController::class, 'index']);
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
Route::get('/tumbang', [App\Http\Controllers\ManajemenTambak\PanenController::class, 'tumbang']);
Route::get('/penaburan_benur', [App\Http\Controllers\ManajemenTambak\PenaburanBenurController::class, 'index']);
Route::get('/simulasi', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'index']);
Route::get('/transaksi_biaya_validasi', [App\Http\Controllers\ManajemenTambak\TransaksiBiayaController::class, 'validasi']);
