<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\SetupCoa;
use App\Models\SetupItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    public function index()
    {
        // penjagaan forbiden
        return view('feature.master.item.index');
    }

    public function datatable()
    {
        $query = SetupItem::with('coa');
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
            'nama_item' => 'required',
            'id_coa' => 'required',
        ]);
        $data = $req->all();
        $coa = SetupCoa::where('id_coa',$req->id_coa)->first();
        $data['kode_coa'] = $coa->kode_coa;
        $insert = SetupItem::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $uuid)
    {
        $benur = SetupItem::where('uuid', $uuid)->firstOrFail();
        $req->validate([
            'nama_item' => 'required',
            'id_coa' => 'required',
        ]);
        $data = $req->all();
        $coa = SetupCoa::where('id_coa',$req->id_coa)->first();
        $data['kode_coa'] = $coa->kode_coa;
        $benur->update($data);
        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function destroy($uuid)
    {
        $benur = SetupItem::where('uuid', $uuid)->delete();
        return response()->json(['success' => true]);
    }

    public function get_coa(){
        $data = SetupCoa::whereRaw("LEFT(kode_coa, 3) = '411' AND RIGHT(kode_coa, 1) <> '0'")->get();
        return response()->json(['success' => true, 'data' => $data]);
    }
}
