<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\MenuModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class MenuController extends Controller
{
    //
    public function index()
    {
        // penjagaan forbiden
        return view('feature.auth.menu.index');
    }

    public function datatable()
    {
        $query = MenuModel::query();
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" id="edit" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" id="hapus" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-danger la la-remove"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function insert(Request $req){
        $req->validate([
            'icon' => 'required',
            'label' => 'required',
            'route_link' => 'required',
        ]);
        $data = $req->all();
        $insert = MenuModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $uuid)
    {
        $benur = MenuModel::where('uuid', $uuid)->firstOrFail();
        $req->validate([
            'icon' => 'required',
            'label' => 'required',
            'route_link' => 'required',
        ]);
        $data = $req->all();
        $benur->update($data);
        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function destroy($uuid)
    {
        $benur = MenuModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }

    public function menu_parent()
    {
        $data = MenuModel::where('is_parent',true)->get();
        return response()->json(['success' => true,'data'=>$data]);
    }

    function collectCheckedMenus($menus, &$result = [])
    {
        foreach ($menus as $menu) {
            if (!empty($menu['checked']) && $menu['checked'] === true) {
                $result[] = [
                    'id_menu'    => $menu['id_menu'],
                    'uuid'       => $menu['uuid'],
                    'label'      => $menu['label'],
                    'route_link' => $menu['route_link'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($menu['items'])) {
                $this->collectCheckedMenus($menu['items'], $result);
            }
        }

        return $result;
    }
}
