<?php

namespace App\Models\Finance;

use App\Models\SetupLokasi;
use App\Models\SetupSupplier;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembelianBarangModel extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy;
    //
    protected $table = 'pembelian_barang';
    protected $primaryKey = 'id_pembelian_barang';
    protected $fillable = [
        'uuid',
        'no_pembelian_barang',
        'id_lokasi',
        'tanggal_pembelian_barang',
        'id_supplier',
        'pembayaran',
        'keterangan',
        'jumlah',
        'total'
    ];

    public function supplier()
    {
        return $this->belongsTo(SetupSupplier::class, 'id_supplier');
    }

    public function lokasi()
    {
        return $this->belongsTo(SetupLokasi::class, 'id_lokasi');
    }
}
