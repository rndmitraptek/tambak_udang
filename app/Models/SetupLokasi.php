<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SetupLokasi extends Model
{
    use SoftDeletes;

    protected $table = 'setup_lokasi';

    protected $fillable = [
        'kode',
        'nama',
        'alamat',
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

    public function siklus()
    {
        return $this->hasMany(SetupSiklus::class, 'lokasi_id');
    }
}