<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\RoleMenuModel;
use App\Models\Auth\RoleModel;
use App\Models\Auth\RoleUserModel;
use App\Models\Auth\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    //
    public function index()
    {
        // penjagaan forbiden
        return view('feature.auth.role.index');
    }

    public function datatable()
    {
        $query = RoleModel::query();
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '
                <a href="javascript:void(0)" id="member" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill"><span><i class="la la-users"></i><span>Member</span></span></a>
                <a href="javascript:void(0)" id="akses" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill"><span><i class="la la-list-alt"></i><span>Akses</span></span></a>
                <a href="javascript:void(0)" id="edit" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" id="hapus" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-danger la la-remove"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function insert(Request $req){
        $req->validate([
            'role' => 'required',
        ]);
        $data = $req->all();
        $insert = RoleModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $uuid)
    {
        $benur = RoleModel::where('uuid', $uuid)->firstOrFail();
        $req->validate([
            'role' => 'required',
        ]);
        $data = $req->all();
        $benur->update($data);
        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function destroy($uuid)
    {
        $benur = RoleModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }

    public function get_menu(Request $req)
    {
        $data = RoleMenuModel::where('id_role',$req->id_role)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function insert_menu(Request $req){
        $req->validate([
            'id_menu' => 'required',
        ]);
        $data = $req->all();
        $insert = RoleMenuModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
    }

    public function destroy_menu($id)
    {
        $benur = RoleMenuModel::where('id+role_menu', $id)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }

    public function get_user_role($id)
    {
        $data = DB::select(
            "SELECT id_user,nama,username from setup_user where id_user not in 
            (select id_user from role_user where id_role = ?)",[$id]);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_user_role_active($id)
    {
        $data = DB::select(
            "SELECT id_user,nama,username from setup_user where id_user in 
            (select id_user from role_user where id_role = ?)",[$id]);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_user(Request $req)
    {
        $data = RoleUserModel::where('id_role',$req->id_role)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function insert_role(Request $req){
        $req->validate([
            'id_user' => 'required',
        ]);
        $data = $req->all();
        $insert = RoleUserModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
    }

    public function destroy_user($id)
    {
        $benur = RoleUserModel::where('id_role_user', $id)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }
}
