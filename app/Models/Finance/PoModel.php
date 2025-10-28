<?php

namespace App\Models\Finance;

use App\Models\Auth\UserModel;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PoModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy,HasUserAudit;

    protected $table = 'po_benur';
    protected $primaryKey = 'id_po_benur';
    protected $fillable = ['uuid','no_po','id_supplier','id_lokasi','id_siklus','tanggal_po','tanggal_kirim','qty','harga_satuan','total','keterangan'];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];
}
