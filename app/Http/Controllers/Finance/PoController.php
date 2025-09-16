<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\PoModel;
use App\Models\SetupLokasi;
use App\Models\SetupSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PoController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.po.index');
    }

    public function datatable()
    {
        $query = PoModel::query()
            ->join('setup_supplier', 'setup_supplier.id', '=', 'po_benur.id_supplier')
            ->join('setup_lokasi', 'setup_lokasi.id', '=', 'po_benur.id_lokasi')
            ->select([
                'po_benur.uuid', 'po_benur.no_po', 'setup_supplier.uuid as uuid_supplier', 'po_benur.tanggal_po','po_benur.tanggal_kirim','po_benur.supplier','po_benur.lokasi',
                'setup_lokasi.uuid as uuid_lokasi', 'po_benur.qty', 'po_benur.harga_satuan', 'po_benur.total','po_benur.keterangan',
            ]);
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" id="edit" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" id="hapus" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-danger la la-remove"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function lokasi(){
        $data = SetupLokasi::all()->makeHidden(['id']);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function supplier(Request $request){
        $query = SetupSupplier::select(['uuid','kode','nama','alamat','telepon','email','nama_perusahaan']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(kode)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(nama)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function insert(Request $req){
        $supplier = SetupSupplier::where('uuid',$req->uuid_supplier)->first();
        $lokasi   = SetupLokasi::where('uuid',$req->uuid_lokasi)->first();
        $req->validate([
            'uuid_supplier' => 'required',
            'tanggal_po'    => 'required',
            'uuid_lokasi'   => 'required',
            'qty'           => 'required',
            'harga_satuan'  => 'required',
            'total'         => 'required',
        ]);
        $data = $req->all();
        unset($data['uuid_supplier']);
        unset($data['uuid_lokasi']);
        $data['id_supplier'] = $supplier->id;
        $data['supplier'] = $supplier->nama;
        $data['id_lokasi'] = $lokasi->id;
        $data['lokasi'] = $lokasi->nama;
        $insert = PoModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $uuid)
    {
        $supplier = SetupSupplier::where('uuid',$req->uuid_supplier)->first();
        $lokasi   = SetupLokasi::where('uuid',$req->uuid_lokasi)->first();
        $benur = PoModel::where('uuid', $uuid)->firstOrFail();
        $req->validate([
            'uuid_supplier' => 'required',
            'tanggal_po'    => 'required',
            'uuid_lokasi'   => 'required',
            'qty'           => 'required',
            'harga_satuan'  => 'required',
            'total'         => 'required',
        ]);
        $data = $req->all();
        $data = $req->all();
        unset($data['uuid_supplier']);
        unset($data['uuid_lokasi']);
        $data['id_supplier'] = $supplier->id;
        $data['supplier'] = $supplier->nama;
        $data['id_lokasi'] = $lokasi->id;
        $data['lokasi'] = $lokasi->nama;
        $benur->update($data);
        return response()->json(['success' => true, 'data' => $benur]);
    }

    public function destroy($uuid)
    {
        $benur = PoModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }
}
