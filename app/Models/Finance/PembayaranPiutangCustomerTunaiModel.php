<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class PembayaranPiutangCustomerTunaiModel extends Model
{
    //
    protected $table = 'pembayaran_piutang_customer_tunai';
    protected $primaryKey = 'id_pembayaran_piutang_customer';
    protected $fillable = ['id_pembayaran_piutang_customer','tanggal_bayar','nama_penerima','nama_pemberi','nominal'];
}
