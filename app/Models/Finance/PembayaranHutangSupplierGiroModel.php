<?php

namespace App\Models\Finance;

use App\Models\SetupRekeningBankModel;
use Illuminate\Database\Eloquent\Model;

class PembayaranHutangSupplierGiroModel extends Model
{
    //
    protected $table = 'pembayaran_hutang_supplier_giro';
    protected $primaryKey = 'id_pembayaran_hutang_supplier_giro';
    protected $fillable = ['id_pembayaran_hutang_supplier','id_rekening_bank','no_giro','terima_giro','jatuh_tempo','nominal','biaya_materai','nominal_materai','selisih_bayar','is_biaya_materai'];

    public function rekening_bank(){
        return $this->belongsTo(SetupRekeningBankModel::class,'id_rekening_bank','id_rekening_bank');
    }
}
