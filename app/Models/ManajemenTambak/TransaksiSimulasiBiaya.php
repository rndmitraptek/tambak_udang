<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupPetak;
use App\Models\SetupBenur;

class TransaksiSimulasiBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi_simulasi_biaya';
    protected $primaryKey = 'id_simulasi_biaya';
    protected $fillable = [
        'trans_simulasi_id',
        'petak_id',
        'benur_id',
        'luas_petak',
        'nominal_biaya',
        'doc',
        'jenis_benur',
        'jumlah_benur',
        'detail',
    ];

    public function simulasi()
    {
        return $this->belongsTo(TransaksiSimulasi::class, 'trans_simulasi_id');
    }

    public function petak()
    {
        return $this->belongsTo(SetupPetak::class, 'petak_id');
    }

    public function benur()
    {
        return $this->belongsTo(SetupBenur::class, 'benur_id');
    }
}
