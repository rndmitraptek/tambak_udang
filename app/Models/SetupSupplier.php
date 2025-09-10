<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SetupSupplier extends Model
{
    use SoftDeletes;

    protected $table = 'setup_supplier';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'telepon',
        'email',
        'nama_perusahaan',
        'catatan',
        'uuid',
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
}