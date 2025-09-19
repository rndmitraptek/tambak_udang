<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class SetupBlok extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy;

    protected $table = 'setup_blok';
    protected $primaryKey = 'id_blok';
    protected $fillable = [
        'lokasi_id',
        'nama_blok',
        'keterangan',
        'uuid',
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

    public function lokasi()
    {
        return $this->belongsTo(SetupLokasi::class, 'lokasi_id');
    }
}