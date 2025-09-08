<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SetupPetak extends Model
{
    use SoftDeletes;

    protected $table = 'setup_petak';

    protected $fillable = [
        'lokasi_id',
        'blok_id',
        'nama',
        'luas',
        'keterangan',
        'uuid',
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

    public function lokasi()
    {
        return $this->belongsTo(SetupLokasi::class, 'lokasi_id');
    }

    public function blok()
    {
        return $this->belongsTo(SetupBlok::class, 'blok_id');
    }
}