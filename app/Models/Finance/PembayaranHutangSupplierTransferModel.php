<?php

namespace App\Models\Finance;

use App\Models\SetupRekeningBankModel;
use Illuminate\Database\Eloquent\Model;

class PembayaranHutangSupplierTransferModel extends Model
{
    //

    protected $table = 'pembayaran_hutang_supplier_transfer';
    protected $primaryKey = 'id_pembayaran_hutang_supplier_transfer';
    protected $fillable = ['id_pembayaran_hutang_supplier','id_rekening_bank','bank_penerima','atas_nama_penerima','no_rekening_penerima','is_biaya_transfer','biaya_transfer','nominal'];

    public function rekening_bank(){
        return $this->belongsTo(SetupRekeningBankModel::class,'id_rekening_bank','id_rekening_bank');
    }
}
