<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HistoryKartuStok extends Model
{

    protected $table = 'history_kartu_stok';
    protected $primaryKey = 'id_kartu';
    protected $fillable = [
        'tanggal',
        'tahun',
        'id_pakan',
        'id_lokasi',
        'transaksi',
        'awal',
        'masuk',
        'keluar',
        'saldo',
        'referensi_no',
        'referensi_id',
    ];

}