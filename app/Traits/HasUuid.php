<?php

namespace App\Traits;

use Illuminate\Support\Str;


trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            $uuidColumn = property_exists($model, 'uuidColumn') ? $model->uuidColumn : 'uuid';

            if (empty($model->{$uuidColumn})) {
                $model->{$uuidColumn} = (string) Str::uuid();
            }
        });
    }
}