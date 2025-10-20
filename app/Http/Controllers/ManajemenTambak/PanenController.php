<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Helpers\GeneradeNomorHelper;
use App\Http\Controllers\Controller;
use App\Models\Finance\PiutangCustomer;
use App\Models\ManajemenTambak\PanenDetailModel;
use App\Models\ManajemenTambak\PanenModel;
use App\Models\SetupBlok;
use App\Models\SetupCustomer;
use App\Models\SetupItem;
use App\Models\SetupLokasi;
use App\Models\SetupPaymentMethod;
use App\Models\SetupPetak;
use App\Models\SetupSiklus;
use App\Models\SetupSiklusPetak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $query = PanenModel::query()
        ->join('setup_siklus', 'panen.id_siklus', '=', 'setup_siklus.id_siklus')
        ->join('setup_lokasi', 'setup_siklus.lokasi_id', '=', 'setup_lokasi.id_lokasi')
        ->join('setup_petak', 'panen.id_petak', '=', 'setup_petak.id_petak')
        ->join('setup_blok', 'setup_petak.blok_id', '=', 'setup_blok.id_blok')
        ->select(['panen.uuid','panen.no_panen','panen.tanggal_panen','panen.jenis_panen','panen.keterangan','panen.jumlah','panen.total',
            'setup_siklus.uuid as uuid_siklus','setup_siklus.nama_siklus',
            'setup_lokasi.nama_lokasi',
            'setup_blok.uuid as uuid_blok','setup_blok.nama_blok',
            'setup_petak.uuid as uuid_petak','setup_petak.nama_petak'
        ]);
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
        ->join('setup_lokasi','setup_siklus.lokasi_id','=','setup_lokasi.id_lokasi')
        ->select(['setup_siklus.uuid','setup_siklus.nama_siklus'])->get();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_blok($uuid_siklus)
    {
        $siklus = SetupSiklus::where('uuid',$uuid_siklus)->first();
        $data = SetupSiklusPetak::where('siklus_id',$siklus->id_siklus)
        ->join('setup_petak','setup_petak.id_petak','=','setup_siklus_petak.petak_id')
        ->join('setup_blok','setup_blok.id_blok','=','setup_petak.blok_id')
        ->groupBy('setup_blok.id_blok','setup_blok.uuid','setup_blok.nama_blok')
        ->select(['setup_blok.id_blok','setup_blok.uuid','setup_blok.nama_blok'])->get();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_petak(Request $req)
    {
        $siklus = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
        $data = SetupBlok::where('setup_blok.uuid',$req->uuid_blok)
        ->join('setup_petak','setup_petak.blok_id','setup_blok.id_blok')
        ->join('setup_siklus_petak','setup_siklus_petak.petak_id','=','setup_petak.id_petak')
        ->where('setup_siklus_petak.siklus_id', $siklus->id_siklus)
        ->select('setup_petak.uuid','setup_petak.nama_petak')->get();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_customer(Request $request){
        $query = SetupCustomer::select(['uuid','kode_customer','nama_customer','alamat_customer','telepon_customer','email_customer']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(kode_customer)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(nama_customer)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function get_item(){
        return response()->json(['success'=>true,'data'=>SetupItem::all()->makeHidden('id_item'),'message'=>'']);
    }
    
    public function get_payment_method(){
        return response()->json(['success'=>true,'data'=>SetupPaymentMethod::all()->makeHidden('id_payment_method'),'message'=>'']);
    }

    public function insert(Request $req){
        DB::beginTransaction();
        try {
            $siklus = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
            $petak = SetupPetak::where('uuid',$req->uuid_petak)->first();
            $req->validate([
                'no_panen'      => 'required',
                'tanggal_panen' => 'required',
                'uuid_siklus'   => 'required',
                'uuid_petak'    => 'required',
                'jenis_panen'   => 'required',
            ]);
            $data = $req->all();
            $data['no_panen'] = GeneradeNomorHelper::long_update('panen');
            unset($data['uuid_siklus']);
            $data['id_siklus'] = $siklus->id_siklus;
            unset($data['uuid_petak']);
            $data['id_petak'] = $petak->id_petak;
            $insert = PanenModel::create($data);
            foreach($req->detail as $d){
                $customer = SetupCustomer::where('uuid',$d['uuid_customer'])->first();
                $item = SetupItem::where('uuid',$d['uuid_item'])->first();
                $paymentMethod = SetupPaymentMethod::where('uuid',$d['uuid_payment_method'])->first();
                unset($d['uuid_customer']);
                unset($d['uuid_item']);
                unset($d['uuid_payment_method']);
                $detail = $d;
                $detail['tanggal_panen'] = $data['tanggal_panen'];
                $detail['id_panen'] = $insert->id_panen;
                $detail['id_customer'] = $customer->id_customer;
                $detail['id_item'] = $item->id_item;
                $detail['id_payment_method']=$paymentMethod->id_payment_method;
                $insert = PanenDetailModel::create($detail);
                if($paymentMethod->id_payment_method==4){
                    // insert piutang Customer
                    $insert_piutang_customer = PiutangCustomer::create([
                        'id_customer'           =>$customer->id_customer,
                        'no_faktur'             =>$data['no_panen'],
                        'reff_id'               =>$insert->id_panen_detail,
                        'reff_trans'            =>'PENJUALAN PANEN',
                        'tanggal_piutang'       =>$data['tanggal_panen'],
                        'tanggal_jatuh_tempo'   =>$data['tanggal_panen'],
                        'jumlah_piutang'        =>$detail['subtotal'],
                        'dibayar'               =>0,
                        'sisa'                  =>$detail['subtotal']
                    ]);
                }
            }
            DB::commit();
            return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
        }catch(\Exception $err) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>$err->getMessage()]);
        }
    }

    public function update(Request $req, $uuid)
    {
        DB::beginTransaction();
        try {
            $siklus = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
            $petak = SetupPetak::where('uuid',$req->uuid_petak)->first();
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
            $data['id_siklus'] = $siklus->id_siklus;
            unset($data['uuid_petak']);
            $data['id_petak'] = $petak->id_petak;
            $panen->update($data);
            $delete_detail = PanenDetailModel::where('id_panen',$panen->id_panen)->delete();
            $delete_piutang = PiutangCustomer::where('no_faktur',$panen->no_panen)->delete();
            foreach($req->detail as $d){
                $customer = SetupCustomer::where('uuid',$d['uuid_customer'])->first();
                $item = SetupItem::where('uuid',$d['uuid_item'])->first();
                $paymentMethod = SetupPaymentMethod::where('uuid',$d['uuid_payment_method'])->first();
                unset($d['uuid_customer']);
                unset($d['uuid_item']);
                unset($d['uuid_payment_method']);
                $detail = $d;
                $detail['id_panen'] = $panen->id_panen;
                $detail['id_customer'] = $customer->id_customer;
                $detail['id_item'] = $item->id_item;
                $detail['id_payment_method']=$paymentMethod->id_payment_method;
                $insert = PanenDetailModel::create($detail);
                if($paymentMethod->id_payment_method==4){
                    // insert piutang Customer
                    $insert_piutang_customer = PiutangCustomer::create([
                        'id_customer'           =>$customer->id_customer,
                        'no_faktur'             =>$data['no_panen'],
                        'reff_id'               =>$insert->id_panen_detail,
                        'reff_trans'            =>'PENJUALAN PANEN',
                        'tanggal_piutang'       =>$data['tanggal_panen'],
                        'tanggal_jatuh_tempo'   =>$data['tanggal_panen'],
                        'jumlah_piutang'        =>$detail['subtotal'],
                        'dibayar'               =>0,
                        'sisa'                  =>$detail['subtotal']
                    ]);
                }
            }
            DB::commit();
            return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
        }catch(\Exception $err) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>$err->getMessage()]);
        }
    }

    public function destroy($uuid)
    {
        $benur = PanenModel::where('uuid', $uuid)->firstOrFail();
        $benurDetail = PanenDetailModel::where('id_panen', $benur->id_panen)->firstOrFail();
        $benurDetail->delete();
        $benur->delete();
        return response()->json(['success' => true]);
    }

    public function get_detail($uuid){
        $panen = PanenModel::where('uuid',$uuid)->first();
        $detail = PanenDetailModel::where('id_panen',$panen->id_panen)
            ->join('setup_customer','setup_customer.id_customer','=','panen_detail.id_customer')
            ->join('setup_payment_method','setup_payment_method.id_payment_method','=','panen_detail.id_payment_method')
            ->join('setup_item','setup_item.id_item','=','panen_detail.id_item')
            ->select([
                'panen_detail.uuid',
                'panen_detail.tanggal_panen',
                'setup_customer.uuid as uuid_customer',
                'setup_customer.nama_customer',
                'setup_payment_method.uuid as uuid_payment_method',
                'setup_item.uuid as uuid_item',
                'panen_detail.harga',
                'panen_detail.jumlah',
                'panen_detail.subtotal',
            ])->get();
        return response()->json(['success'=>true,'data'=>$detail,'message'=>'']);
    }
}
