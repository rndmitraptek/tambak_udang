<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupSiklus;
use App\Models\SetupLokasi;
use App\Models\SetupPetak;
use App\Models\ManajemenTambak\TransaksiSimulasi;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class TransaksiSimulasiDetail extends Model
{

    protected $table = 'transaksi_simulasi_detail';
    protected $primaryKey = 'id_simulasi_detail';
    public $timestamps = true;
    protected $fillable = [
        'id_simulasi',
        'tanggal_simulasi',
        'lokasi_id',
        'siklus_id',
        'petak_id',
        'biomassa',
        'harga_per_kg',
        'total_pendapatan',
        'total_biaya',
        'laba_rugi',
        'hpp_per_kg',
        'fcr',
        'doc',
        'keterangan',
    ];


    public function simulasi()
    {
        return $this->belongsTo(TransaksiSimulasi::class, 'id_simulasi');
    }

    public function siklus()
    {
        return $this->belongsTo(SetupSiklus::class, 'siklus_id');
    }

    public function petak()
    {
        return $this->belongsTo(SetupPetak::class, 'petak_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(SetupLokasi::class, 'lokasi_id');
    }
}
