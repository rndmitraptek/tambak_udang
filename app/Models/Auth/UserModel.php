<?php

namespace App\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\CreatedUpdatedBy;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Authenticatable
{
    //
    use CreatedUpdatedBy;

    protected $table = 'setup_user';
    protected $primaryKey = 'id_user';
    protected $fillable = ['nama','username','password','keterangan'];
    protected $appends = ['menu'];

    public function getMenuAttribute()
    {
        // contoh: query menu sesuai role
        $menu_query = DB::select("
            SELECT mm.id_menu,mm.urut,mm.label,mm.icon,mm.is_parent,mm.id_menu_parent,mm.route_link from setup_user su 
            inner join role_user ru on su.id_user=ru.id_user
            inner join role_menu rm on ru.id_role=rm.id_role
            inner join menu mm on rm.id_menu=mm.id_menu
            where ru.id_user = ?
            group by mm.id_menu,mm.urut,mm.label,mm.icon,mm.is_parent,mm.id_menu_parent,mm.route_link
        ", [$this->id_user]);
        // kalau ada build tree
        return $this->buildMenuTree($menu_query);
    }

    function buildMenuTree($menuItems, $parentId=null){
        $branch = [];

        foreach ($menuItems as $menuItem) {
            if ($menuItem->id_menu_parent === $parentId) {
                $children = $this->buildMenuTree($menuItems, $menuItem->id_menu);
                if ($children) {
                    $menuItem->items = $children;
                }
                $branch[] = $menuItem;
            }
        }

        return $branch;
    }
}
