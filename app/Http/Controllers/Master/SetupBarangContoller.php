<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\SetupBarang;
use App\Models\SetupCoa;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SetupBarangContoller extends Controller
{
    //
    public function index()
    {
        // penjagaan forbiden
        return view('feature.master.barang.index');
    }

    public function datatable()
    {
        $query = SetupBarang::query();
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
            'nama_barang' => 'required',
        ]);
        $data = $req->all();
        if($data['is_activa']){
            $coa = SetupCoa::where('id_coa',$req->id_coa)->first();
            $data['kode_coa'] = $coa->kode_coa;
        }else{
            $data['id_coa'] = null;
            $data['kode_coa'] = null;
        }
        $insert = SetupBarang::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $uuid)
    {
        $benur = SetupBarang::where('uuid', $uuid)->firstOrFail();
        $req->validate([
            'nama_barang' => 'required',
        ]);
        $data = $req->all();
        if($data['is_activa']){
            $coa = SetupCoa::where('id_coa',$req->id_coa)->first();
            $data['kode_coa'] = $coa->kode_coa;
        }else{
            $data['id_coa'] = null;
            $data['kode_coa'] = null;
        }
        $benur->update($data);
        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function destroy($uuid)
    {
        $benur = SetupBarang::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }

    public function get_coa(){
        $data = SetupCoa::whereRaw("LEFT(kode_coa, 2) = '12' AND RIGHT(kode_coa, 1) <> '0' AND nama_coa not like '%AKM.%'")->get();
        return response()->json(['success' => true, 'data' => $data]);
    }
}
