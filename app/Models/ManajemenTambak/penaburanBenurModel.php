<?php

namespace App\Models\ManajemenTambak;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class penaburanBenurModel extends Model
{
    //
    use SoftDeletes,HasUuid,CreatedUpdatedBy,HasUserAudit;
    protected $table = 'penaburan_benur';
    protected $primaryKey = 'id_penaburan_benur';
    protected $fillable = ['uuid','tanggal_penaburan','no_penaburan_benur','id_po_benur','id_lokasi','id_supplier','id_siklus','keterangan',
    'jumlah_bruto','total_nominal_bruto','jumlah_netto','total_nominal_netto','jumlah_actual','total_nominal_actual'];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];

}
