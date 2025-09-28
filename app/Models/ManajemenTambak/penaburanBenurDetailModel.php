<?php

namespace App\Models\ManajemenTambak;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class penaburanBenurDetailModel extends Model
{
    //
    use SoftDeletes,HasUuid,CreatedUpdatedBy;
    protected $table = 'penaburan_benur_detail';
    protected $primaryKey = 'id_penaburan_benur_detail';
    protected $fillable = ['uuid','id_penaburan_benur','id_po_benur','id_petak','id_benur','kode_supplier','jenis_benur','harga_bruto','jumlah_bruto','subtotal_bruto','harga_neto','jumlah_neto','subtotal_neto','harga_actual','jumlah_actual','subtotal_actual'];
}
