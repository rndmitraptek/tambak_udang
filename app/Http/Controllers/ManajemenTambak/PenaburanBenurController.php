<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use App\Models\Finance\PoModel;
use App\Models\ManajemenTambak\penaburanBenurDetailModel;
use App\Models\ManajemenTambak\penaburanBenurModel;
use App\Models\SetupBenur;
use App\Models\SetupLokasi;
use App\Models\SetupPetak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PenaburanBenurController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.penaburan-benur.index');
    }

    public function datatable()
    {
        $query = penaburanBenurModel::query()
            ->join('po_benur', 'po_benur.id_po_benur', '=', 'penaburan_benur.id_po_benur')
            ->select([
                'penaburan_benur.uuid', 'penaburan_benur.no_penaburan_benur', 'po_benur.uuid as uuid_po', 
                'penaburan_benur.tanggal_penaburan','penaburan_benur.no_po','penaburan_benur.supplier','penaburan_benur.lokasi',
                'penaburan_benur.keterangan',
            ]);
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" id="edit" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" id="hapus" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-danger la la-remove"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_po(Request $request){
        $query = PoModel::query()
            ->join('setup_lokasi', 'po_benur.id_lokasi', '=', 'setup_lokasi.id')
            ->select([
                'po_benur.uuid', 'po_benur.no_po', 'po_benur.tanggal_po','po_benur.supplier','po_benur.lokasi','po_benur.qty','po_benur.harga_satuan','po_benur.total','setup_lokasi.uuid as uuid_lokasi',
            ]);
        
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(no_po)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(supplier)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(lokasi)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function get_benur(Request $request){
        $query = SetupBenur::query()->select(['uuid','kode_supplier','jenis','harga']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(kode_supplier)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(jenis)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function get_petak($id_lokasi){
        $lokasi = SetupLokasi::where('uuid',$id_lokasi)->first();
        $data = DB::select("SELECT false as checked, sp.uuid,sb.nama as blok, sp.nama as petak,sp.luas FROM setup_blok sb inner join setup_petak sp on sb.id=sp.blok_id WHERE sb.lokasi_id = ?",[$lokasi->id]);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function insert(Request $req){
        $po = PoModel::where('uuid',$req->uuid_po)->first();
        $req->validate([
            'uuid_po' => 'required',
            'no_penaburan_benur'    => 'required',
            'tanggal_penaburan'   => 'required',
        ]);
        $data = $req->all();
        unset($data['uuid_po']);
        $data['id_po_benur'] = $po->id_po_benur;
        $insert = penaburanBenurModel::create($data);
        foreach($req->detail as $d){
            $benur = SetupBenur::where('uuid',$d['uuid_benur'])->first();
            unset($d['uuid_benur']);
            $petak = SetupPetak::where('uuid',$d['uuid_petak'])->first();
            unset($d['uuid_petak']);
            $data = $d;
            $data['id_benur'] = $benur->id;
            $data['id_petak'] = $petak->id;
            $data['nama_petak'] = $petak->nama;
            $data['kode_supplier'] = $d['kode_benur'];
            $data['id_penaburan_benur'] = $insert->id_penaburan_benur;
            $insert = penaburanBenurDetailModel::create($data);
        }
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function update(Request $req, $uuid)
    {
        $penaburanBenur = penaburanBenurModel::where('uuid', $uuid)->firstOrFail();
        $po = PoModel::where('uuid',$req->uuid_po)->first();
        $req->validate([
            'uuid_po' => 'required',
            'no_penaburan_benur'    => 'required',
            'tanggal_penaburan'   => 'required',
        ]);
        $data = $req->all();
        unset($data['uuid_po']);
        $data['id_po_benur'] = $po->id_po_benur;
        $penaburanBenur->update($data);
        $delete_detail = penaburanBenurDetailModel::where('id_penaburan_benur',$penaburanBenur->id_penaburan_benur)->delete();
        foreach($req->detail as $d){
            $benur = SetupBenur::where('uuid',$d['uuid_benur'])->first();
            unset($d['uuid_benur']);
            $petak = SetupPetak::where('uuid',$d['uuid_petak'])->first();
            unset($d['uuid_petak']);
            $data = $d;
            $data['id_benur'] = $benur->id;
            $data['id_petak'] = $petak->id;
            $data['id_penaburan_benur'] = $penaburanBenur->id_penaburan_benur;
            $insert = penaburanBenurDetailModel::create($data);
        }
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'lahhh...']);
    }

    public function destroy($uuid)
    {
        $benur = penaburanBenurModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }
}
