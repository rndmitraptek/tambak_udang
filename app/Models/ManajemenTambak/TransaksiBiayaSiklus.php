<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use App\Models\SetupSiklus;

class TransaksiBiayaSiklus extends Model
{
    protected $table = 'transaksi_biaya_siklus';
    protected $fillable = ['trans_biaya_id','siklus_id'];

    public function transaksiBiaya()
    {
        return $this->belongsTo(TransaksiBiaya::class, 'trans_biaya_id');
    }

    public function siklus()
    {
        return $this->belongsTo(SetupSiklus::class, 'siklus_id');
    }

    public function petak()
    {
        return $this->hasMany(TransaksiBiayaPetak::class, 'trans_biaya_siklus_id');
    }
}
