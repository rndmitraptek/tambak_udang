<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\SetupRekeningBankModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SetupRekeningBankController extends Controller
{
    //
    public function index()
    {
        // penjagaan forbiden
        return view('feature.master.rekeing-bank.index');
    }

    public function datatable()
    {
        $query = SetupRekeningBankModel::query();
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
            'no_rekening' => 'required',
            'nama_bank' => 'required',
            'atas_nama' => 'required',
        ]);
        $data = $req->all();
        $insert = SetupRekeningBankModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $uuid)
    {
        $benur = SetupRekeningBankModel::where('uuid', $uuid)->firstOrFail();
        $req->validate([
            'no_rekening' => 'required',
            'nama_bank' => 'required',
            'atas_nama' => 'required',
        ]);
        $data = $req->all();
        $benur->update($data);
        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function destroy($uuid)
    {
        $benur = SetupRekeningBankModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }
}
