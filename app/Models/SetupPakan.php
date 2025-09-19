<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class SetupPakan extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy;

    protected $table = 'setup_pakan';
    protected $primaryKey = 'id_pakan';
    protected $fillable = [
        'kode_pakan',
        'nama_pakan',
        'jenis_pakan',
        'merk_pakan',
        'satuan_pakan',
        'harga_pakan',
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
}