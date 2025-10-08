<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SetupSupplier;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class PiutangSupplier extends Model
{
    use CreatedUpdatedBy, HasUuid;

    protected $table = 'piutang_supplier';
    protected $primaryKey = 'id_piutang_supplier';
    protected $fillable = [
        'uuid',
        'id_supplier',
        'no_faktur',
        'reff_id',
        'reff_trans',
        'tanggal_piutang',
        'tanggal_jatuh_tempo',
        'jumlah_piutang',
        'dibayar',
        'sisa',
        'created_by',
        'updated_by',
    ];


    public function supplier()
    {
        return $this->belongsTo(SetupSupplier::class, 'id_supplier');
    }

}
