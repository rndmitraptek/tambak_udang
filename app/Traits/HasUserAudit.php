<?php

namespace App\Traits;

use App\Models\Auth\UserModel;

trait HasUserAudit
{
    /**
     * Memo nama user per-request agar accessor created_by_name / updated_by_name
     * tidak memukul cache store (CACHE_STORE=database) berulang kali saat
     * banyak model di-serialize sekaligus.
     */
    protected static $userNameMemo = [];

    /**
     * Resolve nama user 1x per id dalam satu request (memo statis),
     * lalu fallback ke cache lintas-request.
     */
    protected static function resolveUserName($userId)
    {
        if ($userId === null) {
            return null;
        }

        if (array_key_exists($userId, static::$userNameMemo)) {
            return static::$userNameMemo[$userId];
        }

        return static::$userNameMemo[$userId] = cache()->remember(
            "user_name_{$userId}",
            3600,
            fn() => UserModel::where('id_user', $userId)->value('nama')
        );
    }

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

        // fallback (lazy load 1x saja per ID via memo + cache)
        return static::resolveUserName($this->created_by);
    }

    /**
     * Atribut tambahan: nama pengupdate.
     */
    public function getUpdatedByNameAttribute()
    {
        if ($this->relationLoaded('updater')) {
            return optional($this->updater)->nama;
        }

        return static::resolveUserName($this->updated_by);
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