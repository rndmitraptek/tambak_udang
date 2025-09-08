<?php

namespace App\Models\Auth;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    //
    use HasUuid;

    protected $table = 'role';
    protected $primaryKey = 'id_role';
    protected $fillable = ['uuid','role','keterangan'];
}
