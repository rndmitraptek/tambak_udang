<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;

class SetupCustomer extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy,HasUserAudit;

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
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];

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