<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\HutangSupplierModel;
use App\Models\Finance\PembayaranHutangSupplierDetailHutangModel;
use App\Models\Finance\PembayaranHutangSupplierDetailPiutangModel;
use App\Models\Finance\PembayaranHutangSupplierGiroModel;
use App\Models\Finance\PembayaranHutangSupplierModel;
use App\Models\Finance\PembayaranHutangSupplierTransferModel;
use App\Models\Finance\PembayaranHutangSupplierTunaiModel;
use App\Models\Finance\PiutangSupplierModel;
use App\Models\SetupRekeningBankModel;
use App\Models\SetupSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PembayaranHutangSupplierController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.pembayaran-hutang-supplier.index');
    }

    public function datatable()
    {
        $query = PembayaranHutangSupplierModel::query()
            ->join('setup_supplier','setup_supplier.id_supplier','=','pembayaran_hutang_supplier.id_supplier')
            ->select([
                'pembayaran_hutang_supplier.uuid','pembayaran_hutang_supplier.no_faktur','pembayaran_hutang_supplier.tanggal_bayar','pembayaran_hutang_supplier.id_supplier','pembayaran_hutang_supplier.total_hutang','pembayaran_hutang_supplier.total_piutang','pembayaran_hutang_supplier.total_bayar','pembayaran_hutang_supplier.keterangan','pembayaran_hutang_supplier.file',
                'setup_supplier.uuid as uuid_supplier','setup_supplier.nama_supplier'
            ]);
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0)" id="edit" class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="m--font-warning la la-edit"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
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

    public function rekening(Request $request){
        $query = SetupRekeningBankModel::select(['uuid','nama_bank','atas_nama','no_rekening']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(nama_bank)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(atas_nama)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function get_hutang_piutang($uuid_supplier){
        $supplier = SetupSupplier::where('uuid',$uuid_supplier)->first();
        $hutang = HutangSupplierModel::where('id_supplier',$supplier->id_supplier)->where('sisa','<>',0)->get()->makeHidden(['id_hutang_supplier'])->map(function ($item) {
            $item->checked = false;
            $item->bayar = $item->sisa;
            return $item;
        });
        $piutang = PiutangSupplierModel::where('id_supplier',$supplier->id_supplier)->where('sisa','<>',0)->get()->makeHidden(['id_hutang_supplier'])->map(function ($item) {
            $item->checked = false;
            return $item;
        });
        return response()->json(['success'=>true,'data'=>[
            'hutang' => $hutang,
            'piutang' => $piutang
        ],'message'=>'']);
    }

    public function insert(Request $req){
        DB::beginTransaction();
        try {
            $supplier = SetupSupplier::where('uuid',$req->uuid_supplier)->first();
            $req->validate([
                'uuid_supplier'     => 'required',
                'no_faktur'         => 'required',
                'tanggal_bayar'     => 'required',
            ]);
            $data = $req->all();
            unset($data['uuid_supplier']);
            $data['id_supplier']    = $supplier->id_supplier;
            $insert = PembayaranHutangSupplierModel::create($data);
            foreach($req->hutang as $d){
                $hutangSupplier = HutangSupplierModel::where('uuid',$d['uuid'])->first();
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier']    = $insert->id_pembayaran_hutang_supplier;
                $detail['id_hutang_supplier']               = $hutangSupplier->id_hutang_supplier;
                $detail['nominal_hutang']                   = $d['bayar'];
                $insert_detail = PembayaranHutangSupplierDetailHutangModel::create($detail);
                $hutangSupplier->update([
                    'sisa'=>$hutangSupplier['sisa'] - $detail['nominal_hutang'],
                    'dibayar'=>$hutangSupplier['dibayar'] + $detail['nominal_hutang']
                ]);
            }
            foreach($req->piutang as $d){
                $piutangSupplier = PiutangSupplierModel::where('uuid',$d['uuid'])->first();
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier']    = $insert->id_pembayaran_hutang_supplier;
                $detail['id_piutang_supplier']              = $piutangSupplier->id_piutang_supplier;
                $detail['nominal_piutang']                  = $d['jumlah_piutang'];
                $insert_detail = PembayaranHutangSupplierDetailPiutangModel::create($detail);
                $piutangSupplier->update([
                    'sisa'=>$piutangSupplier['sisa'] - $detail['nominal_piutang'],
                    'dibayar'=>$piutangSupplier['dibayar'] + $detail['nominal_piutang']
                ]);
            }
            foreach($req->transfer as $d){
                $rekening_bank = SetupRekeningBankModel::where('uuid',$d['uuid_rekeing'])->first();
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier'] = $insert->id_pembayaran_hutang_supplier;
                $detail['id_rekening_bank']              = $rekening_bank->id_rekening_bank;
                $insert_transfer = PembayaranHutangSupplierTransferModel::create($detail);
            }
            foreach($req->giro as $d){
                $rekening_bank = SetupRekeningBankModel::where('uuid',$d['uuid_rekeing'])->first();
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier'] = $insert->id_pembayaran_hutang_supplier;
                $detail['id_rekening_bank']              = $rekening_bank->id_rekening_bank;
                $insert_giro = PembayaranHutangSupplierGiroModel::create($detail);
            }
            foreach($req->tunai as $d){
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier'] = $insert->id_pembayaran_hutang_supplier;
                $insert_tunai = PembayaranHutangSupplierTunaiModel::create($detail);
            }
            DB::commit();
            return response()->json(['success'=>true,'data'=>$insert,'message'=>'']);
        }catch(\Exception $err) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>$err->getMessage()]);
        }
    }

    public function update(Request $req,$uuid){
        DB::beginTransaction();
        try {
            $pembayaranHutangSupplier = PembayaranHutangSupplierModel::where('uuid', $uuid)->firstOrFail();
            $supplier = SetupSupplier::where('uuid',$req->uuid_supplier)->first();
            $req->validate([
                'uuid_supplier' => 'required',
                'no_faktur'    => 'required',
                'tanggal_bayar'   => 'required',
            ]);
            $data = $req->all();
            unset($data['uuid_supplier']);
            $data['id_supplier']    = $supplier->id_supplier;
            $pembayaranHutangSupplier->update($data);
            PembayaranHutangSupplierDetailHutangModel::where('id_pembayaran_hutang_supplier',$pembayaranHutangSupplier->id_pembayaran_hutang_supplier)->delete();
            foreach($req->hutang as $d){
                $hutangSupplier = HutangSupplierModel::where('uuid',$d['uuid_hutang'])->first();
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier'] = $pembayaranHutangSupplier->id_pembayaran_hutang_supplier;
                $detail['id_hutang_supplier']    = $hutangSupplier->id_hutang_supplier;
                unset($detail['uuid_hutang']);
                $insert_detail = PembayaranHutangSupplierDetailHutangModel::create($detail);
                $hutangSupplier->update([
                    'sisa'=>$detail['sisa'] - $detail['bayar'],
                    'dibayar'=>$hutangSupplier['dibayar'] + $detail['dibayar']
                ]);
            }
            PembayaranHutangSupplierDetailPiutangModel::where('id_pembayaran_hutang_supplier',$pembayaranHutangSupplier->id_pembayaran_hutang_supplier)->delete();
            foreach($req->piutang as $d){
                $piutangSupplier = PiutangSupplierModel::where('uuid',$d['uuid_piutang'])->first();
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier'] = $pembayaranHutangSupplier->id_pembayaran_hutang_supplier;
                $detail['id_piutang_supplier']    = $piutangSupplier->id_piutang_supplier;
                unset($detail['uuid_hutang']);
                $insert_detail = PembayaranHutangSupplierDetailPiutangModel::create($detail);
                $hutangSupplier->update([
                    'sisa'=>$detail['sisa'] - $detail['bayar'],
                    'dibayar'=>$hutangSupplier['dibayar'] + $detail['dibayar']
                ]);
            }
            PembayaranHutangSupplierTransferModel::where('id_pembayaran_hutang_supplier',$pembayaranHutangSupplier->id_pembayaran_hutang_supplier)->delete();
            foreach($req->transfer as $d){
                $rekening_bank = SetupRekeningBankModel::where('uuid',$d['uuid_rekening_bank'])->first();
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier'] = $pembayaranHutangSupplier->id_pembayaran_hutang_supplier;
                $detail['id_rekening_bank']              = $rekening_bank->id_rekening_bank;
                $insert_transfer = PembayaranHutangSupplierTransferModel::create($detail);
            }
            PembayaranHutangSupplierGiroModel::where('id_pembayaran_hutang_supplier',$pembayaranHutangSupplier->id_pembayaran_hutang_supplier)->delete();
            foreach($req->giro as $d){
                $rekening_bank = SetupRekeningBankModel::where('uuid',$d['uuid_rekening_bank'])->first();
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier'] = $pembayaranHutangSupplier->id_pembayaran_hutang_supplier;
                $detail['id_rekening_bank']              = $rekening_bank->id_rekening_bank;
                $insert_giro = PembayaranHutangSupplierGiroModel::create($detail);
            }
            PembayaranHutangSupplierTunaiModel::where('id_pembayaran_hutang_supplier',$pembayaranHutangSupplier->id_pembayaran_hutang_supplier)->delete();
            foreach($req->tunai as $d){
                $detail = $d;
                $detail['id_pembayaran_hutang_supplier'] = $pembayaranHutangSupplier->id_pembayaran_hutang_supplier;
                $insert_tunai = PembayaranHutangSupplierTunaiModel::create($detail);
            }
            DB::commit();
            return response()->json(['success'=>true,'data'=>$pembayaranHutangSupplier,'message'=>'']);
        }catch(\Exception $err) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>$err->getMessage()]);
        }
    }

    public function detail($uuid){
        $data = PembayaranHutangSupplierModel::with(['Supplier','DetailHutang.HutangSupplier','DetailPiutang.PiutangSupplier','Transfer.rekening_bank','Tunai','Giro.rekening_bank'])
        ->where('uuid',$uuid)->first();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function destroy($uuid)
    {
        $benur = PembayaranHutangSupplierModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }
}
