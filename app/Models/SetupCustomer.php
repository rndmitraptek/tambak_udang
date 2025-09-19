<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;

class SetupCustomer extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy;

    protected $table = 'setup_customer';
    protected $primaryKey = 'id_customer';
    protected $fillable = [
        'kode_customer',
        'nama_customer',
        'alamat_customer',
        'telepon_customer',
        'email_customer',
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