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
    Route::prefix('pembayaran_hutang_supplier')->name('pembayaran_hutang_supplier.')->group(function(){
        Route::get('/datatable',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class, 'datatable'])->name('datatable');
        Route::post('/insert',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class, 'insert'])->name('insert');
        Route::post('/update/{uuid}',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class, 'update'])->name('update');
        Route::get('/supplier',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class, 'supplier'])->name('supplier');
        Route::get('/rekening',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class, 'rekening'])->name('rekening');
        Route::get('/get_hutang_piutang/{id_supplier}',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class, 'get_hutang_piutang'])->name('get_hutang_piutang');
        Route::get('/get_detail/{uuid}',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class, 'detail'])->name('get_detail');
        Route::delete('/delete/{uuid}',[App\Http\Controllers\Finance\PembayaranHutangSupplierController::class, 'destroy'])->name('delete');
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
        Route::get('/batal/{uuid}',[App\Http\Controllers\Finance\PembelianPakanController::class, 'batal'])->name('batal');
        Route::get('/detail/{uuid}',[App\Http\Controllers\Finance\PembelianPakanController::class, 'detail'])->name('detail');
        
    });
    Route::prefix('penggunaan_pakan')->name('penggunaan_pakan.')->group(function(){
        Route::post('/insert',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'insert'])->name('insert');
        Route::post('/update/{uuid}',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'update'])->name('update');
        Route::get('/datatable',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'datatable'])->name('datatable');
        Route::delete('/delete/{uuid}',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'destroy'])->name('delete');
        Route::get('/get_po',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'get_po'])->name('get_po');
        Route::get('/get_petak/{uuid_siklus}',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'get_petak'])->name('get_petak');
        Route::get('/get_benur',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'get_benur'])->name('get_benur');
        Route::get('/get_detail/{uuid}',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'get_detail'])->name('get_detail');
        Route::get('/lokasi',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'lokasi'])->name('lokasi');
        Route::get('/siklus/{id_lokasi}',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'get_siklus'])->name('siklus');
        Route::get('/pakan/{id_lokasi}',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'get_pakan'])->name('pakan');
        Route::get('/batal/{uuid}',[App\Http\Controllers\ManajemenTambak\PenggunaanPakanController::class, 'batal'])->name('batal');
    });

    Route::prefix('retur_pakan')->name('retur_pakan.')->group(function(){
        Route::post('/insert',[App\Http\Controllers\Finance\ReturPakanController::class, 'insert'])->name('insert');
        Route::post('/update/{uuid}',[App\Http\Controllers\Finance\ReturPakanController::class, 'update'])->name('update');
        Route::get('/datatable',[App\Http\Controllers\Finance\ReturPakanController::class, 'datatable'])->name('datatable');
        Route::delete('/delete/{uuid}',[App\Http\Controllers\Finance\ReturPakanController::class, 'destroy'])->name('delete');
        Route::get('/get_pembelian',[App\Http\Controllers\Finance\ReturPakanController::class, 'get_pembelian'])->name('get_pembelian');
        Route::get('/get_pembelian_detail/{uuid}',[App\Http\Controllers\Finance\ReturPakanController::class, 'get_pembelian_detail'])->name('get_pembelian_detail');
        Route::get('/get_benur',[App\Http\Controllers\Finance\ReturPakanController::class, 'get_benur'])->name('get_benur');
        Route::get('/get_detail/{uuid}',[App\Http\Controllers\Finance\ReturPakanController::class, 'get_detail'])->name('get_detail');
        Route::get('/lokasi',[App\Http\Controllers\Finance\ReturPakanController::class, 'lokasi'])->name('lokasi');
        Route::get('/batal/{uuid}',[App\Http\Controllers\Finance\ReturPakanController::class, 'batal'])->name('batal');
    });
});