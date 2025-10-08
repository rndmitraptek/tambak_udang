<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class PembayaranHutangSupplierTunaiModel extends Model
{
    //
    protected $table = 'pembayaran_hutang_supplier_tunai';
    protected $primaryKey = 'id_pembayaran_hutang_supplier_tunai';
    protected $fillable = ['id_pembayaran_hutang_supplier','tanggal_bayar','nama_penerima','nama_pemberi','nominal'];

}
