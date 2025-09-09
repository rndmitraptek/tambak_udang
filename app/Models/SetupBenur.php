<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SetupBenur extends Model
{
    use SoftDeletes;

    protected $table = 'setup_benur';

    protected $fillable = [
        'kode',
        'kode_supplier',
        'jenis',
        'harga',
        'keterangan',
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