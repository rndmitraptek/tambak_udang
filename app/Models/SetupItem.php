<?php

namespace App\Models;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class SetupItem extends Model
{
    use HasUuid,CreatedUpdatedBy;
    //
    protected $table = 'setup_item';
    protected $primaryKey = 'id_item';
    protected $fillable = ['nama_item'];
}
