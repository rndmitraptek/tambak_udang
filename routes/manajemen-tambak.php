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