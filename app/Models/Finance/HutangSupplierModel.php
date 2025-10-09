<?php

namespace App\Models\Finance;

use App\Models\SetupSupplier;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class HutangSupplierModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;

    protected $table = 'hutang_supplier';
    protected $primaryKey = 'id_hutang_supplier';
    protected $fillable = ['uuid','id_supplier','no_faktur','reff_id','reff_trans','tanggal_hutang','tanggal_jatuh_tempo','jumlah_hutang','dibayar','sisa'];
    
    public function supplier()
    {
        return $this->belongsTo(SetupSupplier::class, 'id_supplier');
    }
}
