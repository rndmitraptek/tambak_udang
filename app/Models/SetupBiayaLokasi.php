<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SetupBiayaLokasi extends Model
{
    use SoftDeletes;

    protected $table = 'setup_biaya_lokasi';
    protected $primaryKey = 'id_biaya_lokasi';
    // protected $primaryKey = 'id';
    protected $fillable = [
        'biaya_id',
        'lokasi_id'
    ];
    public $timestamps = false;


    public function biaya()
    {
        return $this->belongsTo(SetupBiaya::class, 'biaya_id', 'id_biaya');
    }
}