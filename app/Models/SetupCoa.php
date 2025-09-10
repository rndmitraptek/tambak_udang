<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SetupCoa extends Model
{
    use SoftDeletes;

    protected $table = 'setup_coa';

    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'pos_laporan',
        'kode_parent',
        'saldo_normal',
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