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
    protected $fillable = ['uuid','id_panen','tanggal_panen','id_customer','customer','id_metode_pembayaran','metode_pembayaran','item','harga','jumlah','subtotal'];
}
