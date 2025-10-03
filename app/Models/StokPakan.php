<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\SetupPakan;

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

    public function pakan()
    {
        return $this->belongsTo(SetupPakan::class,'pakan_id', 'id_pakan');
    }
}