<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SetupBiaya extends Model
{
    use SoftDeletes;

    protected $table = 'setup_biaya';

    protected $fillable = [
        'uuid',
        'kode',
        'nama',
        'kelompok',
        'petak_id',
        'periode',
        'nominal',
        'coa_id',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

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