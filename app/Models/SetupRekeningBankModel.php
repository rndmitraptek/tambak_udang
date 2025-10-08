<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SetupRekeningBankModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;
    protected $table = 'setup_rekening_bank';
    protected $primaryKey = 'id_rekening_bank';
    protected $fillable = [
        'no_rekening',
        'nama_bank',
        'atas_nama',
        'kode_coa'
    ];
}
