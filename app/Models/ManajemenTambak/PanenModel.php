<?php

namespace App\Models\ManajemenTambak;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanenModel extends Model
{
    //
    use SoftDeletes,HasUuid,CreatedUpdatedBy;
    protected $table = 'panen';
    protected $primaryKey = 'id_panen';
    protected $fillable = ['uuid','no_panen','tanggal_panen','id_siklus','id_petak','jenis_panen','keterangan','jumlah','total'];
}
