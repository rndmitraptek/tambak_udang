<?php

namespace App\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;

class JurnalDetailModel extends Model
{
    //
    protected $table = 'jurnal_detail';
    protected $primaryKey = 'id_jurnal_detail';
    protected $fillable = [
        'id_jurnal',
        'id_coa',
        'kode_coa',
        'nama_coa',
        'debit',
        'kredit'
    ];
}
