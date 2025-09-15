<?php

namespace App\Models\Finance;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PoModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;

    protected $table = 'po_benur';
    protected $primaryKey = 'id_po_benur';
    protected $fillable = ['uuid','no_po','id_supplier','supplier','tanggal_po','tanggal_kirim','id_lokasi','lokasi','qty','harga_satuan','total'];
}
