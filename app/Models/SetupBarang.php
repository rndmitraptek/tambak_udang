<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetupBarang extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy,HasUserAudit;
    //
    protected $table = 'setup_barang';
    protected $primaryKey = 'id_barang';
    protected $fillable = [
        'nama_barang',
        'is_activa',
        'id_coa',
        'kode_coa',
        'harga'
    ];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];
}
