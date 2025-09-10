<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    //
    public function index()
    {
        // penjagaan forbiden
        return view('feature.auth.user.index');
    }

    public function login()
    {
        return view('feature.auth.login.index');
    }

    public function datatable()
    {
        $query = UserModel::query();
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
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        $data = $req->all();
        $data['password'] = Hash::make($data['password']);
        $insert = UserModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function cek_login(Request $req){
        $user = UserModel::where('username',$req->username)->first();
        if(!$user){
            return response()->json(['success'=>false,'data'=>null,'message'=>'username tidak di temukan']);
        }
        if(!Hash::check($req->password, $user->password)){
            return response()->json(['success'=>false,'data'=>null,'message'=>'password salah']);
        }
        unset($user->password);
        Auth::guard('web')->login($user);
        request()->session()->regenerate();

        return response()->json(['success'=>true,'data'=>$user,'message'=>'']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
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

    public function update(Request $req, $id)
    {
        $benur = UserModel::where('id_user', $id)->firstOrFail();
        $req->validate([
            'nama' => 'required',
            'username' => 'required',
        ]);
        $data = $req->all();
        unset($data['password']);
        $benur->update($data);
        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function destroy($id)
    {
        $benur = UserModel::where('id_user', $id)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }
}
