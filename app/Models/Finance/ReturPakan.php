<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupLokasi;
use App\Models\SetupSiklus;
use App\Models\Finance\ReturPakanDetail;
use App\Models\Finance\PembelianPakan;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;

class ReturPakan extends Model
{
    use SoftDeletes, CreatedUpdatedBy, HasUuid, HasUserAudit;

    protected $table = 'retur_pakan';
    protected $primaryKey = 'id_retur';
    protected $fillable = [
        'uuid','no_retur','tanggal_retur',
        'lokasi_id',
        'siklus_id',
        'pembelian_id',
        'total',
        'keterangan',
        'created_by',
        'updated_by',
    ];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];

    public function detail()
    {
        return $this->hasMany(ReturPakanDetail::class, 'id_retur', 'id_retur');
    }


    public function pembelian()
    {
        return $this->belongsTo(PembelianPakan::class, 'pembelian_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(SetupLokasi::class, 'lokasi_id');
    }

    public function siklus()
    {
        return $this->belongsTo(SetupSiklus::class, 'siklus_id');
    }
}
