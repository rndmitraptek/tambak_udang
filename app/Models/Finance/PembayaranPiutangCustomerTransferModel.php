<?php

namespace App\Models\Finance;

use App\Models\SetupRekeningBankModel;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutangCustomerTransferModel extends Model
{
    //
    protected $table = 'pembayaran_piutang_customer_transfer';
    protected $primaryKey = 'id_pembayaran_piutang_customer_transfer';
    protected $fillable = ['id_pembayaran_piutang_customer','id_rekening_bank','bank_pengirim','atas_nama_pengirim','no_rekening_pengirim','is_biaya_transfer','biaya_transfer','nominal'];

    public function rekening_bank(){
        return $this->belongsTo(SetupRekeningBankModel::class,'id_rekening_bank','id_rekening_bank');
    }

}
