<?php

namespace App\Models\Auth;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class RoleUserModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;

    protected $table = 'role_user';
    protected $primaryKey = 'id_role_user';
    protected $fillable = ['id_role','id_user'];
}
