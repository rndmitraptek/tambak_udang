<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;

class PembayaranPiutangCustomerDetailModel extends Model
{
    //
    protected $table = 'pembayaran_piutang_customer_detail';
    protected $primaryKey = 'id_pembayaran_piutang_customer_detail';
    protected $fillable = ['id_pembayaran_piutang_customer_detail','id_pembayaran_piutang_customer','id_piutang_customer','nominal_piutang'];

    public function PiutangCustomer(){
        return $this->belongsTo(PiutangCustomer::class, 'id_piutang_customer','id_piutang_customer');
    }

}
