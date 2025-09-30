<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupSupplier;
use App\Models\SetupLokasi;
use App\Models\SetupSiklus;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class PembelianPakan extends Model
{
    use SoftDeletes, CreatedUpdatedBy, HasUuid;

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
        'created_by',
        'updated_by',
    ];

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
