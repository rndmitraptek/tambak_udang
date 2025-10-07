<?php

namespace App\Models\Finance;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PiutangSupplierModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;

    protected $table = 'piutang_supplier';
    protected $primaryKey = 'id_piutang_supplier';
    protected $fillable = ['uuid','no_po','id_supplier','no_faktur','reff_id','reff_trans','tanggal_piutang','tanggal_jatuh_tempo','jumlah_piutang','dibayar','sisa'];
}
