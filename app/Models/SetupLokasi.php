<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use App\Traits\LogActivity;

class SetupLokasi extends Model
{
    use SoftDeletes,HasUuid,CreatedUpdatedBy,LogActivity,HasUserAudit;

    protected $table = 'setup_lokasi';
    protected $primaryKey = 'id_lokasi';
    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
        'alamat_lokasi',
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

    public function siklus()
    {
        return $this->hasMany(SetupSiklus::class, 'lokasi_id');
    }
}