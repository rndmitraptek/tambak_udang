<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\GeneradeNomorHelper;
use App\Http\Controllers\Controller;
use App\Models\Finance\PembayaranPiutangCustomerDetailModel;
use App\Models\Finance\PembayaranPiutangCustomerGiroModel;
use App\Models\Finance\PembayaranPiutangCustomerModel;
use App\Models\Finance\PembayaranPiutangCustomerTransferModel;
use App\Models\Finance\PembayaranPiutangCustomerTunaiModel;
use App\Models\Finance\PiutangCustomer;
use App\Models\SetupCustomer;
use App\Models\SetupRekeningBankModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PembayaranPiutangCustomerController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.pembayaran-piutang-customer.index');
    }

    public function datatable()
    {
        $query = PembayaranPiutangCustomerModel::query()
            ->join('setup_customer','setup_customer.id_customer','=','pembayaran_piutang_customer.id_customer')
            ->select([
                'pembayaran_piutang_customer.uuid','pembayaran_piutang_customer.no_faktur','pembayaran_piutang_customer.tanggal_bayar','pembayaran_piutang_customer.id_customer','pembayaran_piutang_customer.total_bayar','pembayaran_piutang_customer.keterangan','pembayaran_piutang_customer.file',
                'setup_customer.uuid as uuid_customer','setup_customer.nama_customer',
                'pembayaran_piutang_customer.created_by','pembayaran_piutang_customer.updated_by','pembayaran_piutang_customer.created_at','pembayaran_piutang_customer.updated_at'
            ]);
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" id="edit" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function customer(Request $request){
        $query = SetupCustomer::select(['uuid','kode_customer','nama_customer','alamat_customer','telepon_customer','email_customer']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(kode_customer)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(nama_customer)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function rekening(Request $request){
        $query = SetupRekeningBankModel::select(['uuid','nama_bank','atas_nama','no_rekening']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(nama_bank)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(atas_nama)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function get_piutang($uuid_customer){
        $customer = SetupCustomer::where('uuid',$uuid_customer)->first();
        $piutang = PiutangCustomer::where('id_customer',$customer->id_customer)->where('sisa','<>',0)->get()->makeHidden(['id_piutang_customer'])->map(function ($item) {
            $item->checked = false;
            $item->bayar = $item->sisa;
            return $item;
        });
        return response()->json(['success'=>true,'data'=>[
            'piutang' => $piutang
        ],'message'=>'']);
    }

    public function insert(Request $req){
        DB::beginTransaction();
        try {
            $customer = SetupCustomer::where('uuid',$req->uuid_customer)->first();
            $req->validate([
                'uuid_customer'     => 'required',
                'no_faktur'         => 'required',
                'tanggal_bayar'     => 'required',
            ]);
            $data = $req->all();
            $data['no_faktur'] = GeneradeNomorHelper::long_update('pembayaran_hutang_customer');
            $data['id_customer']    = $customer->id_customer;
            $insert = PembayaranPiutangCustomerModel::create($data);
            foreach($req->piutang as $d){
                $piutangCustomer = PiutangCustomer::where('uuid',$d['uuid'])->first();
                $detail = $d;
                $detail['id_pembayaran_piutang_customer']   = $insert->id_pembayaran_piutang_customer;
                $detail['id_piutang_customer']              = $piutangCustomer->id_piutang_customer;
                $detail['nominal_piutang']                  = $d['bayar'];
                $insert_detail = PembayaranPiutangCustomerDetailModel::create($detail);
                $piutangCustomer->update([
                    'sisa'=>$piutangCustomer['sisa'] - $detail['nominal_piutang'],
                    'dibayar'=>$piutangCustomer['dibayar'] + $detail['nominal_piutang']
                ]);
            }
            foreach($req->transfer as $d){
                $rekening_bank = SetupRekeningBankModel::where('uuid',$d['uuid_rekeing'])->first();
                $detail = $d;
                $detail['id_pembayaran_piutang_customer']   = $insert->id_pembayaran_piutang_customer;
                $detail['id_rekening_bank']              = $rekening_bank->id_rekening_bank;
                $insert_transfer = PembayaranPiutangCustomerTransferModel::create($detail);
            }
            foreach($req->giro as $d){
                // $rekening_bank = SetupRekeningBankModel::where('uuid',$d['uuid_rekeing'])->first();
                $detail = $d;
                $detail['id_pembayaran_piutang_customer']   = $insert->id_pembayaran_piutang_customer;
                // $detail['id_rekening_bank']              = $rekening_bank->id_rekening_bank;
                $detail['id_rekening_bank']                 = null;
                $insert_giro = PembayaranPiutangCustomerGiroModel::create($detail);
            }
            foreach($req->tunai as $d){
                $detail = $d;
                $detail['id_pembayaran_piutang_customer']   = $insert->id_pembayaran_piutang_customer;
                $insert_tunai = PembayaranPiutangCustomerTunaiModel::create($detail);
            }
            DB::commit();
            return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
        }catch(\Exception $err) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>$err->getMessage()]);
        }
    }

    public function detail($uuid){
        $data = PembayaranPiutangCustomerModel::with(['Customer','Detail.PiutangCustomer','Transfer.rekening_bank','Tunai','Giro.rekening_bank'])
        ->where('uuid',$uuid)->first();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }
    
    public function get_coa(){
        $data = DB::select("SELECT * FROM setup_coa WHERE LEFT(kode_coa, 3) = ('111') AND RIGHT(kode_coa, 1) <> '0'",[]);
        return response()->json(['success' => true, 'data' => $data]);
    }
}
