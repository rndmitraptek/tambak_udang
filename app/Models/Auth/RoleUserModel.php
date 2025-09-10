<?php

namespace App\Models\Auth;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class RoleUserModel extends Model
{
    //
    use CreatedUpdatedBy;

    protected $table = 'role_user';
    protected $primaryKey = 'id_role_user';
    protected $fillable = ['id_role','id_user'];

    public function user(){
        return $this->hasMany(UserModel::class,'id_user','id_user');
    }
}
