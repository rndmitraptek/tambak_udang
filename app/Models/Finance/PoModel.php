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
    protected $fillable = ['uuid','no_po','id_supplier','id_lokasi','id_siklus','tanggal_po','tanggal_kirim','qty','harga_satuan','total','keterangan'];
}
