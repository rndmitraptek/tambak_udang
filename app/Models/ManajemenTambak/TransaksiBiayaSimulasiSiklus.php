<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use App\Models\SetupSiklus;

class TransaksiBiayaSimulasiSiklus extends Model
{
    protected $table = 'transaksi_biaya_simulasi_siklus';
    protected $fillable = ['trans_biaya_simulasi_id','siklus_id'];

    public function transaksiBiaya()
    {
        return $this->belongsTo(TransaksiBiayaSimulasi::class, 'trans_biaya_simulasi_id');
    }

    public function siklus()
    {
        return $this->belongsTo(SetupSiklus::class, 'siklus_id');
    }

    public function petak()
    {
        return $this->hasMany(TransaksiBiayaSimulasiPetak::class, 'trans_biaya_simulasi_siklus_id');
    }
}
