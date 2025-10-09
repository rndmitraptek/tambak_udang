<?php

namespace App\Models\Finance;

use App\Models\SetupCustomer;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PiutangCustomer extends Model
{
    //
    use CreatedUpdatedBy, HasUuid;
    protected $table = 'piutang_customer';
    protected $primaryKey = 'id_piutang_customer';
    protected $fillable = [
        'uuid',
        'id_customer',
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
        return $this->belongsTo(SetupCustomer::class, 'id_customer');
    }
}
