<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/master.php';
require __DIR__.'/manajemen-tambak.php';
Route::get('health',function(){
    return response()->json(['success'=>true,'data'=>'health']);
});
Route::get('/', [App\Http\Controllers\Auth\UsersController::class,'login']);
Route::get('login', [App\Http\Controllers\Auth\UsersController::class,'login'])->name('login');
Route::get('/user',[App\Http\Controllers\Auth\UsersController::class, 'index']);
Route::middleware('auth')->group(function () {
    Route::get('/lokasi', [App\Http\Controllers\Master\LokasiController::class, 'index']);
    Route::get('/item', [App\Http\Controllers\Master\ItemController::class, 'index']);
    Route::get('/rekening_bank', [App\Http\Controllers\Master\SetupRekeningBankController::class, 'index']);
    Route::get('/payment_method', [App\Http\Controllers\Master\PaymentMethodController::class, 'index']);
    Route::get('/blok', [App\Http\Controllers\Master\BlokController::class, 'index']);
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
    Route::get('/retur_pakan', [App\Http\Controllers\Finance\ReturPakanController::class, 'index']);
    Route::get('/penggunaan_pakan', [App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'index']);
    Route::get('/simulasi', [App\Http\Controllers\ManajemenTambak\SimulasiController::class, 'index']);
    Route::get('/transaksi_biaya_validasi', [App\Http\Controllers\ManajemenTambak\TransaksiBiayaController::class, 'validasi']);
    Route::get('/menu',[App\Http\Controllers\Auth\MenuController::class, 'index']);
    Route::get('/role',[App\Http\Controllers\Auth\RoleController::class, 'index']);
    Route::get('/logout', [App\Http\Controllers\Auth\UsersController::class,'logout']);
    Route::get('/pembayaran_hutang_supplier',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class,'index']);
    Route::get('/pembayaran_piutang_customer',[App\Http\Controllers\Finance\PembayaranPiutangCustomerController::class,'index']);
});

