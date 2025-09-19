<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupSiklus;
use App\Models\SetupLokasi;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class TransaksiSimulasi extends Model
{
    use SoftDeletes, CreatedUpdatedBy, HasUuid;

    protected $table = 'transaksi_simulasi';
    protected $primaryKey = 'id_simulasi';
    protected $fillable = [
        'uuid',
        'siklus_id',
        'lokasi_id',
        'tanggal_simulasi',
        'catatan',
        'created_by',
        'updated_by'
    ];

    public function biaya()
    {
        return $this->hasMany(TransaksiSimulasiBiaya::class, 'trans_simulasi_id');
    }

    public function pendapatan()
    {
        return $this->hasMany(TransaksiSimulasiPendapatan::class, 'trans_simulasi_id');
    }

    public function siklus()
    {
        return $this->belongsTo(SetupSiklus::class, 'siklus_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(SetupLokasi::class, 'lokasi_id');
    }
}
