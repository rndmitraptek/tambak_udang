<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use App\Models\ManajemenTambak\PanenDetailModel;
use App\Models\ManajemenTambak\PanenModel;
use App\Models\SetupBlok;
use App\Models\SetupCustomer;
use App\Models\SetupLokasi;
use App\Models\SetupPetak;
use App\Models\SetupSiklus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PanenController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.panen.index');
    }

    public function datatable()
    {
        $query = PanenModel::query();
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" id="edit" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>
                <a href="javascript:void(0)" id="hapus" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-danger la la-remove"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get_siklus()
    {
        $data = SetupSiklus::where('status','OPEN')
        ->join('setup_lokasi','setup_siklus.id_lokasi','=','setup_lokasi.id')
        ->select(['setup_lokasi.nama as lokasi','setup_siklus.uuid','setup_siklus.nama'])->get();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_blok($uuid_lokasi)
    {
        $lokasi = SetupLokasi::where('uuid',$uuid_lokasi)->first();
        $data = SetupBlok::where('id_lokasi',$lokasi->id)->get();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_petak($uuid_blok)
    {
        $blok = SetupBlok::where('uuid',$uuid_blok)->first();
        $data = SetupPetak::where('id_blok',$blok->id)->get();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function insert(Request $req){
        $siklus = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
        $blok = SetupBlok::where('uuid',$req->uuid_blok)->first();
        $petak = SetupPetak::where('uuid',$req->uuid_petak)->first();
        $lokasi = SetupLokasi::where('id',$siklus->id_lokasi)->first();
        $req->validate([
            'no_panen'      => 'required',
            'tanggal_panen' => 'required',
            'uuid_siklus'   => 'required',
            'uuid_blok'     => 'required',
            'uuid_petak'    => 'required',
            'jenis_panen'   => 'required',
        ]);
        $data = $req->all();
        unset($data['uuid_siklus']);
        $data['id_siklus'] = $siklus->id;
        $data['siklus'] = $siklus->nama;
        unset($data['uuid_blok']);
        $data['id_blok'] = $blok->id;
        $data['blok'] = $blok->nama;
        unset($data['uuid_petak']);
        $data['id_petak'] = $petak->id;
        $data['petak'] = $petak->nama;
        $data['id_lokasi'] = $lokasi->id;
        $data['lokasi'] = $lokasi->nama;
        $insert = PanenModel::create($data);
        foreach($req->detail as $d){
            $customer = SetupCustomer::where('uuid',$d['uuid_customer'])->first();
            unset($d['uuid_customer']);
            $data['id_panen'] = $insert->id_panen;
            $data['id_customer'] = $customer->id;
            $data['customer'] = $customer->nama;
            $insert = PanenDetailModel::create($data);
        }
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
    }

    public function update(Request $req, $uuid)
    {
        $siklus = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
        $blok = SetupBlok::where('uuid',$req->uuid_blok)->first();
        $petak = SetupPetak::where('uuid',$req->uuid_petak)->first();
        $lokasi = SetupLokasi::where('id',$siklus->id_lokasi)->first();
        $panen = PanenModel::where('uuid', $uuid)->firstOrFail();
        $req->validate([
            'no_panen'      => 'required',
            'tanggal_panen' => 'required',
            'uuid_siklus'   => 'required',
            'uuid_blok'     => 'required',
            'uuid_petak'    => 'required',
            'jenis_panen'   => 'required',
        ]);
        $data = $req->all();
        unset($data['uuid_siklus']);
        $data['id_siklus'] = $siklus->id;
        $data['siklus'] = $siklus->nama;
        unset($data['uuid_blok']);
        $data['id_blok'] = $blok->id;
        $data['blok'] = $blok->nama;
        unset($data['uuid_petak']);
        $data['id_lokasi'] = $lokasi->id;
        $data['lokasi'] = $lokasi->nama;
        $data['id_petak'] = $petak->id;
        $data['petak'] = $petak->nama;
        $panen->update($data);
        $delete_detail = PanenDetailModel::where('id_panen',$panen->id_panen)->delete();
        foreach($req->detail as $d){
            $customer = SetupCustomer::where('uuid',$d['uuid_customer'])->first();
            unset($d['uuid_customer']);
            $data['id_panen'] = $panen->id_panen;
            $data['id_customer'] = $customer->id;
            $data['customer'] = $customer->nama;
            $insert = PanenDetailModel::create($data);
        }
        return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
    }

    public function destroy($uuid)
    {
        $benur = PanenModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }

    public function get_detail($uuid){
        $panen = PanenModel::where('uuid',$uuid)
        ->join('setup_siklus','panen.id_siklus','=','setup_siklus.id')
        ->join('setup_blok','panen.id_blok','=','setup_blok.id')
        ->join('setup_petak','panen.id_petak','=','setup_petak.id')            
        ->first();
        $detail = PanenDetailModel::where('id_panen',$panen->id_panen)
            ->join('setup_customer','panen_detail.id_customer','=','setup_customer.id')
            ->select([
                'panen_detail.uuid',
                'panen_detail.tanggal_panen',
                'panen_detail.id_customer',
                'panen_detail.customer',
                'panen_detail.id_metode_pembayaran',
                'panen_detail.metode_pembayaran',
                'panen_detail.item',
                'panen_detail.harga',
                'panen_detail.jumlah',
                'panen_detail.subtotal',
                'setup_customer.uuid as uuid_customer',
            ])->get();
        return response()->json(['success'=>true,'data'=>$detail,'message'=>'']);
    }
}
