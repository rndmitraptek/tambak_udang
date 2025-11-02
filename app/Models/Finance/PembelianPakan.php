<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupSupplier;
use App\Models\SetupLokasi;
use App\Models\SetupSiklus;
use App\Models\Finance\PembelianPakanDetail;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;

class PembelianPakan extends Model
{
    use SoftDeletes, CreatedUpdatedBy, HasUuid,HasUserAudit;

    protected $table = 'pembelian_pakan';
    protected $primaryKey = 'id_pembelian';
    protected $fillable = [
        'uuid','no_pembelian','tanggal_pembelian',
        'supplier_id',
        'lokasi_id',
        'siklus_id',
        'jumlah_item',
        'total',
        'keterangan',
        'id_coa',
        'kode_coa',
        'created_by',
        'updated_by',
    ];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];

    public function detail()
    {
        return $this->hasMany(PembelianPakanDetail::class, 'id_pembelian', 'id_pembelian');
    }

    public function supplier()
    {
        return $this->belongsTo(SetupSupplier::class, 'supplier_id');
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
