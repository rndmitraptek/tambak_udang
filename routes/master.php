<?php

use Illuminate\Support\Facades\Route;

// GROUP SETUP LOKASI
Route::prefix('lokasi')->name('lokasi.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\LokasiController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\LokasiController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\LokasiController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\LokasiController::class, 'destroy'])->name('delete');
});

// GROUP SETUP BLOK
Route::prefix('blok')->name('blok.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\BlokController::class, 'index'])->name('index');
    Route::get('/lokasi-list', [App\Http\Controllers\Master\BlokController::class, 'lokasiList'])->name('lokasi-list');
    Route::get('/data', [App\Http\Controllers\Master\BlokController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\BlokController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\BlokController::class, 'destroy'])->name('delete');
});

// GROUP SETUP PETAK
Route::prefix('petak')->name('petak.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\PetakController::class, 'index'])->name('index');
    Route::get('/lokasi-list', [App\Http\Controllers\Master\PetakController::class, 'lokasiList'])->name('lokasi-list');
    Route::get('/blok-list-by-lokasi/{lokasi_id}', [App\Http\Controllers\Master\PetakController::class, 'blokListByLokasi'])->name('blok-list-by-lokasi');
    Route::get('/blok-list', [App\Http\Controllers\Master\PetakController::class, 'blokList'])->name('blok-list');
    Route::get('/data', [App\Http\Controllers\Master\PetakController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\PetakController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\PetakController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\PetakController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\PetakController::class, 'destroy'])->name('delete');
});

// GROUP SETUP BENUR
Route::prefix('benur')->name('benur.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\BenurController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\BenurController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\BenurController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\BenurController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\BenurController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\BenurController::class, 'destroy'])->name('delete');
});

// GROUP SETUP PAKAN
Route::prefix('pakan')->name('pakan.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\PakanController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\PakanController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\PakanController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\PakanController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\PakanController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\PakanController::class, 'destroy'])->name('delete');
});

// GROUP SETUP CUSTOMER
Route::prefix('customer')->name('customer.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\CustomerController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\CustomerController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\CustomerController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\CustomerController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\CustomerController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\CustomerController::class, 'destroy'])->name('delete');
});

// GROUP SETUP SUPPLIER
Route::prefix('supplier')->name('supplier.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\SupplierController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\SupplierController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\SupplierController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\SupplierController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\SupplierController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\SupplierController::class, 'destroy'])->name('delete');
});

// GROUP SETUP BIAYA
Route::prefix('setup-biaya')->name('setup-biaya.')->group(function() {
    Route::get('/', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'destroy'])->name('delete');
    Route::get('/coa-list', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'coaList'])->name('coa-list');
    Route::get('/lokasi-list', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'lokasiList'])->name('lokasi-list');
    Route::get('/petak-list', [App\Http\Controllers\Akuntansi\SetupBiayaController::class, 'petakList'])->name('petak-list');
});

// GROUP SETUP COA
Route::prefix('coa')->name('coa.')->group(function() {
    Route::get('/', [App\Http\Controllers\Master\CoaController::class, 'index'])->name('index');
    Route::get('/data', [App\Http\Controllers\Master\CoaController::class, 'data'])->name('data');
    Route::post('/store', [App\Http\Controllers\Master\CoaController::class, 'store'])->name('store');
    Route::get('/show/{uuid}', [App\Http\Controllers\Master\CoaController::class, 'show'])->name('show');
    Route::post('/update/{uuid}', [App\Http\Controllers\Master\CoaController::class, 'update'])->name('update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\Master\CoaController::class, 'destroy'])->name('delete');
    Route::get('/parent-list', [App\Http\Controllers\Master\CoaController::class, 'parentList'])->name('parent-list');
});

// GROUP SETUP SIKLUS
Route::prefix('setup-siklus')->group(function () {
    Route::get('/', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'index'])->name('siklus.index');
    Route::get('/lokasi-list', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'lokasiList'])->name('siklus.lokasi-list');
    Route::get('/petak-list', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'petakList'])->name('siklus.petak-list');
    Route::get('/data', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'data'])->name('siklus.data');
    Route::post('/store', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'store'])->name('siklus.store');
    Route::get('/show/{uuid}', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'show'])->name('siklus.show');
    Route::post('/update/{uuid}', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'update'])->name('siklus.update');
    Route::delete('/delete/{uuid}', [App\Http\Controllers\ManajemenTambak\SiklusController::class, 'destroy'])->name('siklus.delete');
});