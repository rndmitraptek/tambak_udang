<?php

namespace App\Models\Finance;

use App\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;

class PembayaranHutangSupplierDetailPiutangModel extends Model
{
    //

    protected $table = 'pembayaran_hutang_supplier_detail_piutang';
    protected $primaryKey = 'id_pembayaran_hutang_supplier_detail_piutang';
    protected $fillable = ['id_pembayaran_hutang_supplier','id_piutang_supplier','nominal_piutang'];

    public function PiutangSupplier(){
        return $this->belongsTo(PiutangSupplierModel::class, 'id_piutang_supplier','id_piutang_supplier');
    }
}
