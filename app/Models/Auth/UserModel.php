<?php

namespace App\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Authenticatable
{
    //
    use CreatedUpdatedBy;

    protected $table = 'setup_user';
    protected $primaryKey = 'id_user';
    protected $fillable = ['nama','username','password','keterangan'];
}
