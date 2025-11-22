<?php

namespace App\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;

class JurnalDetailModel extends Model
{
    //
    protected $table = 'jurnal_detail';
    protected $primaryKey = 'id_jurnal_detail';
    protected $appends = ['nominal_value'];
    protected $fillable = [
        'id_jurnal',
        'id_coa',
        'kode_coa',
        'nama_coa',
        'debit',
        'kredit'
    ];

    public function getNominalValueAttribute()
    {
        if ($this->debit > 0) {
            return $this->debit;
        }

        if ($this->kredit > 0) {
            return $this->kredit;
        }

        return 0;
    }
}
