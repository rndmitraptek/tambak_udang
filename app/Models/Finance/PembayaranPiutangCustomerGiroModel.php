<?php

namespace App\Models\Finance;

use App\Models\SetupRekeningBankModel;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutangCustomerGiroModel extends Model
{
    //
    protected $table = 'pembayaran_piutang_customer_giro';
    protected $primaryKey = 'id_pembayaran_piutang_customer_giro';
    protected $fillable = ['id_pembayaran_piutang_customer','id_rekening_bank','no_giro','terima_giro','jatuh_tempo','nominal','biaya_materai','nominal_materai','selisih_bayar','is_biaya_materai'];

    public function rekening_bank(){
        return $this->belongsTo(SetupRekeningBankModel::class,'id_rekening_bank','id_rekening_bank');
    }

}
