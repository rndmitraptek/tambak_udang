<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\UserModel;
use Illuminate\Http\Request;
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
            'keterangan' => 'required',
        ]);
        $data = $req->all();
        $data['password'] = Hash::make($data['password']);
        $insert = UserModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $id)
    {
        $benur = UserModel::where('id_user', $id)->firstOrFail();
        $req->validate([
            'nama' => 'required',
            'username' => 'required',
            'keterangan' => 'required',
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
