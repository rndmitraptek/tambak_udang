<?php

namespace App\Models\Auth;

use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    //
    use HasUuid,CreatedUpdatedBy;

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';
    protected $fillable = ['uuid','urut','label','icon','route_link','is_parent','id_menu_parent'];
}
