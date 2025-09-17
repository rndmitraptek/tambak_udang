<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupBiaya;
use App\Models\SetupCoa;

class TransaksiBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'transaksi_biaya';
    protected $fillable = [
        'uuid','no_transaksi','tanggal_transaksi','tanggal_mulai','tanggal_selesai',
        'biaya_id','nominal','coa_id','keterangan','created_by','updated_by','validated_by', 'validated_at'
    ];

    public function biaya()
    {
        return $this->belongsTo(SetupBiaya::class, 'biaya_id');
    }

    public function coa()
    {
        return $this->belongsTo(SetupCoa::class, 'coa_id');
    }

    public function siklus()
    {
        return $this->hasMany(TransaksiBiayaSiklus::class, 'trans_biaya_id');
    }
}
