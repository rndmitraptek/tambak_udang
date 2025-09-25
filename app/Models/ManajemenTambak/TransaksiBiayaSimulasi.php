<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupBiaya;
use App\Models\SetupCoa;
use App\Models\ManajemenTambak\TransaksiSimulasi;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class TransaksiBiayaSimulasi extends Model
{
    use SoftDeletes, CreatedUpdatedBy, HasUuid;

    protected $table = 'transaksi_biaya_simulasi';
    protected $fillable = [
        'uuid','no_transaksi','tanggal_transaksi','tanggal_mulai','tanggal_selesai',
        'biaya_id','nominal','coa_id','keterangan','created_by','updated_by','validated_by', 'validated_at','id_simulasi'
    ];

    public function biaya()
    {
        return $this->belongsTo(SetupBiaya::class, 'biaya_id');
    }

    public function coa()
    {
        return $this->belongsTo(SetupCoa::class, 'coa_id');
    }

    public function simulasi()
    {
        return $this->belongsTo(TransaksiSimulasi::class, 'id_simulasi');
    }

    public function siklus()
    {
        return $this->hasMany(TransaksiBiayaSimulasiSiklus::class, 'trans_biaya_simulasi_id');
    }
}
