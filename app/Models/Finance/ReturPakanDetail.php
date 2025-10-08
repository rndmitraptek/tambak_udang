<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupPakan;

class ReturPakanDetail extends Model
{

    protected $table = 'retur_pakan_detail';
    protected $primaryKey = 'id_retur_detail';
    protected $fillable = [
        'id_retur',
        'pakan_id',
        'harga_per_kg',
        'jumlah_retur',
        'subtotal',
    ];

    public function retur()
    {
        return $this->belongsTo(ReturPakan::class, 'id_retur');
    }

    public function pakan()
    {
        return $this->belongsTo(SetupPakan::class, 'pakan_id');
    }
}
