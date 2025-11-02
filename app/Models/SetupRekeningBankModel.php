<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SetupRekeningBankModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy,HasUserAudit;
    protected $table = 'setup_rekening_bank';
    protected $primaryKey = 'id_rekening_bank';
    protected $fillable = [
        'no_rekening',
        'nama_bank',
        'atas_nama',
        'kode_coa',
        'id_coa',
    ];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];

    public function coa()
    {
        return $this->belongsTo(SetupCoa::class, 'id_coa','id_coa');
    }
}
