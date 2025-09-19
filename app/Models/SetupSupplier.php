<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class SetupSupplier extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy;

    protected $table = 'setup_supplier';
    protected $primaryKey = 'id_supplier';
    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat_supplier',
        'telepon_supplier',
        'email_supplier',
        'nama_perusahaan',
        'catatan',
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