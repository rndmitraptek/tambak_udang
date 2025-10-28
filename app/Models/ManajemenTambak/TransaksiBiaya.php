<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupBiaya;
use App\Models\SetupCoa;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;

class TransaksiBiaya extends Model
{
    use SoftDeletes, CreatedUpdatedBy, HasUuid, HasUserAudit;

    protected $table = 'transaksi_biaya';
    protected $fillable = [
        'uuid','no_transaksi','tanggal_transaksi','tanggal_mulai','tanggal_selesai',
        'biaya_id','nominal','coa_id','keterangan','created_by','updated_by','validated_by', 'validated_at',
        'reff_id','reff_trans'
    ];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];

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
