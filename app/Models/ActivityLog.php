<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    //
    protected $fillable = [
        'table_name',
        'keterangan',
        'uuid',
        'action',
        'data',
        'user_id',
    ];
}
