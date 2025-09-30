<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StokPakan extends Model
{

    protected $table = 'stok_pakan';
    protected $primaryKey = 'id_stok_pakan';
    protected $fillable = [
        'pakan_id',
        'lokasi_id',
        'stok',
        'uuid',
    ];

}