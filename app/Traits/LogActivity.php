<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


trait LogActivity
{
    protected static function bootLogActivity()
    {
        // updating created_by and updated_by when model is created
        static::created(function ($model) {
            ActivityLog::create([
                'table_name' => $model->getTable(),
                'record_id' => $model->uuid,
                'action' => 'insert',
                'data' => json_encode($model->toArray()),
                'user_id' => (Auth::user())?Auth::user()->id_user:1
            ]);
        });

        // updating updated_by when model is updated
        static::updated(function ($model) {
            ActivityLog::create([
                'table_name' => $model->getTable(),
                'record_id' => $model->uuid,
                'action' => 'update',
                'data' => json_encode($model->toArray()),
                'user_id' => (Auth::user())?Auth::user()->id_user:1
            ]);
        });
    }
}