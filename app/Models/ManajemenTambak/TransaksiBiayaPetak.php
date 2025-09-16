<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use App\Models\SetupPetak;

class TransaksiBiayaPetak extends Model
{
    protected $table = 'transaksi_biaya_petak';
    protected $fillable = ['trans_biaya_id','trans_biaya_siklus_id','petak_id','biaya_id','luas','persentase','nominal_petak','tanggal_mulai','tanggal_selesai'];

    public function transaksiBiaya()
    {
        return $this->belongsTo(TransaksiBiaya::class, 'trans_biaya_id');
    }

    public function siklus()
    {
        return $this->belongsTo(TransaksiBiayaSiklus::class, 'trans_biaya_siklus_id');
    }

    public function petak()
    {
        return $this->belongsTo(SetupPetak::class, 'petak_id');
    }

    public function biaya()
    {
        return $this->belongsTo(SetupBiaya::class, 'biaya_id');
    }
}
