<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\GeneradeNomorHelper;
use App\Http\Controllers\Controller;
use App\Models\Finance\PoModel;
use App\Models\SetupLokasi;
use App\Models\SetupSiklus;
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
            ->join('setup_supplier', 'setup_supplier.id_supplier', '=', 'po_benur.id_supplier')
            ->join('setup_siklus','setup_siklus.id_siklus','=','po_benur.id_siklus')
            ->join('setup_lokasi', 'setup_lokasi.id_lokasi', '=', 'po_benur.id_lokasi')
            ->select([
                'po_benur.uuid', 'po_benur.no_po', 'setup_supplier.uuid as uuid_supplier', 'po_benur.tanggal_po','po_benur.tanggal_kirim','setup_supplier.nama_supplier','setup_siklus.uuid as uuid_siklus','setup_siklus.nama_siklus',
                'setup_lokasi.nama_lokasi','setup_lokasi.uuid as uuid_lokasi', 'po_benur.qty', 'po_benur.harga_satuan', 'po_benur.total','po_benur.keterangan',
                'po_benur.created_by','po_benur.updated_by','po_benur.created_at','po_benur.updated_at'
            ]);
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" id="edit" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" id="hapus" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-danger la la-remove"></i></a>';
            })
            ->filter(function ($query) {
                $search = strtolower(request()->get('search')['value'] ?? '');
                if ($search) {
                    $query->where(function($q) use ($search) {
                        $q->whereRaw('LOWER(po_benur.no_po) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw("LOWER(to_char(po_benur.tanggal_po, 'YYYY-MM-DD')) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw("LOWER(to_char(po_benur.tanggal_kirim, 'YYYY-MM-DD')) LIKE ?", ["%{$search}%"])
                        ->orWhereRaw('LOWER(setup_supplier.nama_supplier) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(setup_lokasi.nama_lokasi) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(setup_siklus.nama_siklus) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(po_benur.keterangan) LIKE ?', ["%{$search}%"]);
                    });
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_siklus($uuid_lokasi)
    {
        $lokasi = SetupLokasi::where('uuid',$uuid_lokasi)->first();
        if($lokasi){
            $data = SetupSiklus::where('status','OPEN')
            ->where('lokasi_id',$lokasi->id_lokasi)
            ->select(['uuid','nama_siklus'])->get();
            return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
        }else{
            return response()->json(['success'=>false,'data'=>null,'message'=>'ERROR!, uuid lokasi tidak di temukan']);
        }
    }

    public function lokasi(){
        $data = SetupLokasi::all()->makeHidden(['id_lokasi']);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function supplier(Request $request){
        $query = SetupSupplier::select(['uuid','kode_supplier','nama_supplier','alamat_supplier','telepon_supplier','email_supplier','nama_perusahaan']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(kode_supplier)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(nama_supplier)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function insert(Request $req){
        $supplier = SetupSupplier::where('uuid',$req->uuid_supplier)->first();
        $lokasi   = SetupLokasi::where('uuid',$req->uuid_lokasi)->first();
        $siklus   = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
        $req->validate([
            'uuid_supplier' => 'required',
            'uuid_lokasi'   => 'required',
            'uuid_siklus'   => 'required',
            'tanggal_po'    => 'required',
            'uuid_lokasi'   => 'required',
            'qty'           => 'required',
            'harga_satuan'  => 'required',
            'total'         => 'required',
        ]);
        $data = $req->all();
        $data['no_po'] = GeneradeNomorHelper::long_update('po_benur');
        unset($data['uuid_supplier']);
        unset($data['uuid_lokasi']);
        unset($data['siklus']);
        $data['id_supplier'] = $supplier->id_supplier;
        $data['id_lokasi'] = $lokasi->id_lokasi;
        $data['id_siklus'] = $siklus->id_siklus;
        $insert = PoModel::create($data);
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $uuid)
    {
        $supplier = SetupSupplier::where('uuid',$req->uuid_supplier)->first();
        $lokasi   = SetupLokasi::where('uuid',$req->uuid_lokasi)->first();
        $siklus   = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
        $benur = PoModel::where('uuid', $uuid)->firstOrFail();
        $req->validate([
            'uuid_supplier' => 'required',
            'uuid_lokasi'   => 'required',
            'uuid_siklus'   => 'required',
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
        unset($data['siklus']);
        $data['id_supplier'] = $supplier->id_supplier;
        $data['id_lokasi'] = $lokasi->id_lokasi;
        $data['id_siklus'] = $siklus->id_siklus;
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
