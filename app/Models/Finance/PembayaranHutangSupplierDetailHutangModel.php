<?php

namespace App\Models\Finance;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PembayaranHutangSupplierDetailHutangModel extends Model
{
    //
    use CreatedUpdatedBy;

    protected $table = 'pembayaran_hutang_supplier_detail_hutang';
    protected $primaryKey = 'id_pembayaran_hutang_supplier_detail_hutang';
    protected $fillable = ['id_pembayaran_hutang_supplier','id_hutang_supplier','nominal_hutang'];

    public function HutangSupplier(){
        return $this->belongsTo(HutangSupplierModel::class, 'id_hutang_supplier','id_hutang_supplier');
    }
}
