<?php

namespace App\Models\Akuntansi;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class JurnalModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;
    protected $table = 'jurnal';
    protected $primaryKey = 'id_jurnal';
    protected $fillable = [
        'tanggal',
        'no_bukti',
        'reff_id',
        'reff_trans',
        'keterangan'
    ];
}
