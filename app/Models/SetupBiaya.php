<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class SetupBiaya extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy;

    protected $table = 'setup_biaya';
    protected $primaryKey = 'id_biaya';
    // protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'kode_biaya',
        'nama_biaya',
        'kelompok_biaya',
        'petak_id',
        'periode_biaya',
        'nominal_biaya',
        'coa_id',
        'catatan',
        'created_by',
        'updated_by',
    ];

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //         if (empty($model->uuid)) {
    //             $model->uuid = (string) Str::uuid();
    //         }
    //     });
    // }

    // Relasi ke petak (jika kelompok = Perpetak)
    public function petak()
    {
        return $this->belongsTo(SetupPetak::class, 'petak_id');
    }

    // Relasi ke COA
    public function coa()
    {
        return $this->belongsTo(SetupCoa::class, 'coa_id');
    }

    // Relasi many-to-many ke lokasi
    public function lokasi()
    {
        return $this->belongsToMany(
            SetupLokasi::class,
            'setup_biaya_lokasi',
            'biaya_id',
            'lokasi_id'
        );
    }
}