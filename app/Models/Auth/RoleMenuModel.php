<?php

namespace App\Models\Auth;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class RoleMenuModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;

    protected $table = 'role_menu';
    protected $primaryKey = 'id_role_menu';
    protected $fillable = ['id_role','id_menu'];
}
