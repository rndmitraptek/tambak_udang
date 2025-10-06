<?php

namespace App\Models\ManajemenTambak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenggunaanPakanDetail extends Model
{
    protected $table = 'penggunaan_pakan_detail';
    protected $primaryKey = 'id_penggunaan_detail';
    protected $fillable = [
        'id_penggunaan',
        'pakan_id',
        'petak_id',
        'jumlah',
        'harga_per_kg',
        'subtotal',
    ];
}
