<?php

namespace App\Models\Finance;

use App\Models\SetupBarang;
use Illuminate\Database\Eloquent\Model;

class PembelianBarangDetailModel extends Model
{
    //
    protected $table = 'pembelian_barang_detail';
    protected $primaryKey = 'id_pembelian_barang_detail';
    protected $fillable = [
        'id_pembelian_barang',
        'id_barang',
        'harga',
        'qty',
        'subtotal'
    ];

    public function detail(){
        return $this->hasMany(PembelianBarangModel::class, 'id_pembelian_barang','id_pembelian_barang');
    }

    public function barang()
    {
        return $this->belongsTo(SetupBarang::class, 'id_barang');
    }
}
