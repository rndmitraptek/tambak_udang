<?php

namespace App\Models\Akuntansi;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class JurnalModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy,HasUserAudit;
    protected $table = 'jurnal';
    protected $primaryKey = 'id_jurnal';
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];
    protected $fillable = [
        'tanggal',
        'no_bukti',
        'reff_id',
        'reff_trans',
        'keterangan'
    ];

    public function detail()
    {
        return $this->hasMany(JurnalDetailModel::class, 'id_jurnal', 'id_jurnal');
    }
}
