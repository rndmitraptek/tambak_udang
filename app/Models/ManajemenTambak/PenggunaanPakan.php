<?php

namespace App\Models\ManajemenTambak;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenggunaanPakan extends Model
{
    //
    use SoftDeletes,HasUuid,CreatedUpdatedBy,HasUserAudit;
    protected $table = 'penggunaan_pakan';
    protected $primaryKey = 'id_penggunaan';
    protected $fillable = [
        'uuid',
        'no_penggunaan',
        'tanggal_penggunaan',
        'waktu',
        'lokasi_id',
        'siklus_id',
        'jumlah_petak',
        'total',
        'keterangan',
        'created_by',
        'updated_by',
    ];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];

    public function detail()
    {
        return $this->hasMany(PenggunaanPakanDetail::class, 'id_penggunaan', 'id_penggunaan');
    }
}
