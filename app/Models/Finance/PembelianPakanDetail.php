<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupPakan;

class PembelianPakanDetail extends Model
{

    protected $table = 'pembelian_pakan_detail';
    protected $primaryKey = 'id_pembelian_detail';
    protected $fillable = [
        'id_pembelian',
        'id_pakan',
        'harga',
        'jumlah',
        'subtotal',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianPakan::class, 'id_pembelian');
    }

    public function pakan()
    {
        return $this->belongsTo(SetupPakan::class, 'id_pakan');
    }
}
