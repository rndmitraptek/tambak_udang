<?php

namespace App\Models\ManajemenTambak;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanenDetailModel extends Model
{
    //
    use SoftDeletes,HasUuid,CreatedUpdatedBy;
    protected $table = 'panen_detail';
    protected $primaryKey = 'id_panen_detail';
    protected $fillable = ['uuid','tanggal_panen','id_panen','id_customer','id_payment_method','id_item','id_coa','kode_coa','harga','jumlah','subtotal'];
}
