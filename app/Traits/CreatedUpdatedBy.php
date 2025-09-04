<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Auth;

trait CreatedUpdatedBy
{
    public static function bootCreatedUpdatedBy()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = (Auth::user())?Auth::user()->id_user:1;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = (Auth::user())?Auth::user()->id_user:1;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = (Auth::user())?Auth::user()->id_user:1;
            }
        });
    }
}