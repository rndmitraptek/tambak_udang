<?php

namespace App\Traits;

use App\Models\Auth\UserModel;

trait HasUserAudit
{
    /**
     * Relasi ke user pembuat data.
     */
    public function creator()
    {
        return $this->belongsTo(UserModel::class, 'created_by');
    }

    /**
     * Relasi ke user pengubah data.
     */
    public function updater()
    {
        return $this->belongsTo(UserModel::class, 'updated_by');
    }

    /**
     * Atribut tambahan: nama pembuat.
     */
    public function getCreatedByNameAttribute()
    {
        // gunakan relasi yang sudah eager-loaded
        if ($this->relationLoaded('creator')) {
            return optional($this->creator)->nama;
        }

        // fallback (lazy load 1x saja per ID via cache)
        return cache()->remember(
            "user_name_{$this->created_by}",
            3600,
            fn() => UserModel::where('id_user', $this->created_by)->value('nama')
        );
    }

    /**
     * Atribut tambahan: nama pengupdate.
     */
    public function getUpdatedByNameAttribute()
    {
        if ($this->relationLoaded('updater')) {
            return optional($this->updater)->nama;
        }

        return cache()->remember(
            "user_name_{$this->updated_by}",
            3600,
            fn() => UserModel::where('id_user', $this->updated_by)->value('nama')
        );
    }

    /**
     * Atribut tambahan: format tanggal pembuatan.
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * Atribut tambahan: format tanggal update.
     */
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null;
    }

    /**
     * Boot trait agar model otomatis eager load creator & updater
     * jika diaktifkan lewat properti $withUserAudit.
     */
    protected static function bootHasUserAudit()
    {
        static::retrieved(function ($model) {
            if (property_exists($model, 'withUserAudit') && $model->withUserAudit === true) {
                $model->loadMissing(['creator:id_user,nama', 'updater:id_user,nama']);
            }
        });
    }
}