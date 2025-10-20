<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetupBarang extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy;
    //
    protected $table = 'setup_barang';
    protected $primaryKey = 'id_barang';
    protected $fillable = [
        'nama_barang',
        'harga'
    ];
}
