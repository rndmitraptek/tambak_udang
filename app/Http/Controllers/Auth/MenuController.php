<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\MenuModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class MenuController extends Controller
{
    //
    public function index()
    {
        // penjagaan forbiden
        return view('feature.auth.menu.index');
    }

    public function insert(Request $req){
        $data = $req->all();
        $insert = MenuModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
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
}
