<?php

namespace App\Models\Auth;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    //
    use CreatedUpdatedBy;

    protected $table = 'setup_user';
    protected $primaryKey = 'id_user';
    protected $fillable = ['nama','username','password','keterangan'];
}
