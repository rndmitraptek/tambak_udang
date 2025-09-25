<?php

namespace App\Models\ManajemenTambak;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class penaburanBenurModel extends Model
{
    //
    use SoftDeletes,HasUuid,CreatedUpdatedBy;
    protected $table = 'penaburan_benur';
    protected $primaryKey = 'id_penaburan_benur';
    protected $fillable = ['uuid','tanggal_penaburan','no_penaburan_benur','id_po_benur','no_po','supplier','lokasi','keterangan',
    'jumlah_bruto','total_nominal_bruto','jumlah_netto','total_nominal_netto','jumlah_actual','total_nominal_actual'];
}
