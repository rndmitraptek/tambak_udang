<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupPetak;

class TransaksiSimulasiPendapatan extends Model
{
    // use SoftDeletes;

    protected $table = 'transaksi_simulasi_pendapatan';
    protected $primaryKey = 'id_simulasi_pendapatan';
    protected $fillable = [
        'trans_simulasi_id',
        'petak_id',
        'harga_per_kg',
        'biomassa',
        'biomassa_actual',
        'biomassa_subtotal',
        'pendapatan_simulasi',
        'pendapatan_actual_partial',
        'pendapatan_subtotal',
    ];

    public function simulasi()
    {
        return $this->belongsTo(TransaksiSimulasi::class, 'trans_simulasi_id');
    }

    public function petak()
    {
        return $this->belongsTo(SetupPetak::class, 'petak_id');
    }

}
