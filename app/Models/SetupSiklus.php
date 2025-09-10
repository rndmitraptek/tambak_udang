<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SetupSiklus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'setup_siklus';
    protected $primaryKey = 'id';
    protected $fillable = [
        'uuid',
        'lokasi_id',
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'catatan',
        'status',
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

    public function lokasi()
    {
        return $this->belongsTo(SetupLokasi::class, 'lokasi_id');
    }

    public function siklusPetak()
    {
        return $this->hasMany(SetupSiklusPetak::class, 'siklus_id');
    }

    public function petak()
    {
        return $this->belongsToMany(
            SetupPetak::class,
            'setup_siklus_petak',
            'siklus_id',
            'petak_id'
        );
    }
}
