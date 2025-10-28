<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUserAudit;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SetupItem extends Model
{
    use HasUuid,CreatedUpdatedBy,HasUserAudit;
    //
    protected $table = 'setup_item';
    protected $primaryKey = 'id_item';
    protected $fillable = ['nama_item'];
    protected $appends = ['created_by_name', 'updated_by_name','created_at_formatted','updated_at_formatted'];
}
