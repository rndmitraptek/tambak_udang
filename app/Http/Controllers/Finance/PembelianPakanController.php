<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\PembelianPakan;
use App\Models\Finance\PembelianPakanDetail;
use App\Models\HistoryKartuStok;
use App\Models\SetupBenur;
use App\Models\SetupLokasi;
use App\Models\SetupPetak;
use App\Models\SetupSiklus;
use App\Models\SetupSupplier;
use App\Models\SetupPakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Helpers\StokHelper;

class PembelianPakanController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.pembelian_pakan.index');
    }

    public function datatable(Request $request)
    {
        $query = PembelianPakan::with(['supplier','lokasi'])
            ->orderBy('id_pembelian', 'desc');

        return DataTables::of($query)
            ->addColumn('supplier', fn($row) => $row->supplier->nama_supplier ?? '-')
            ->addColumn('lokasi', fn($row) => $row->lokasi->nama_lokasi ?? '-')
            ->addColumn('siklus', fn($row) => $row->siklus->nama_siklus ?? '-')
            ->addColumn('actions', function ($row) use ($request) {
                
                return '
                    <a href="javascript:void(0)" onclick="angular.element(this).scope().editTransaksi(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-warning m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Edit">
                        <i class="m--font-warning la la-edit"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deleteTransaksi(\''.$row->uuid.'\')" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" 
                        title="Hapus">
                        <i class="m--font-danger la la-remove"></i>
                    </a>
                ';
            })
            ->rawColumns(['siklus','actions'])
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

    public function pakan(){
        $data = SetupPakan::all();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_supplier(Request $request){
        $query = SetupSupplier::select(['uuid','kode_supplier','nama_supplier','alamat_supplier','telepon_supplier','email_supplier','nama_perusahaan']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(kode_supplier)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(nama_supplier)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function get_po(Request $request){
        $query = PoModel::query()
            ->join('setup_lokasi', 'po_benur.id_lokasi', '=', 'setup_lokasi.id_lokasi')
            ->join('setup_supplier', 'setup_supplier.id_supplier', '=', 'po_benur.id_supplier')
            ->join('setup_siklus', 'setup_siklus.id_siklus', '=', 'po_benur.id_siklus')
            ->select([
                'po_benur.uuid', 'po_benur.no_po', 'po_benur.tanggal_po','po_benur.qty','po_benur.harga_satuan','po_benur.total',
                'setup_supplier.nama_supplier','setup_supplier.uuid as uuid_supplier',
                'setup_lokasi.uuid as uuid_lokasi','setup_lokasi.nama_lokasi',
                'setup_siklus.uuid as uuid_siklus','setup_siklus.nama_siklus',
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
        $query = SetupBenur::query()->select(['uuid','kode_supplier','jenis_benur as jenis','harga_benur as harga']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(kode_supplier)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(jenis)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function get_petak($id_siklus){
        $siklus = SetupSiklus::where('uuid',$id_siklus)->first();
        $data = DB::select("SELECT false as checked, sp.uuid,sb.nama_blok as blok, sp.nama_petak as petak,sp.luas_petak as luas FROM setup_blok sb 
                    inner join setup_petak sp on sb.id_blok=sp.blok_id
                    inner join setup_siklus_petak ssp on sp.id_petak=ssp.petak_id
                    where ssp.siklus_id = ? and sp.deleted_at is null",[$siklus->id_siklus]);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function insert(Request $req){
        DB::beginTransaction();
        try {
            $supplier = SetupSupplier::where('uuid', $req->header['uuid_supplier'])->firstOrFail();
            $lokasi   = SetupLokasi::where('uuid', $req->header['uuid_lokasi'])->firstOrFail();
            $siklus   = SetupSiklus::where('uuid', $req->header['uuid_siklus'])->firstOrFail();
            $validator = Validator::make($req->header, [
                'no_pembelian'      => 'required',
                'tanggal_pembelian' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['success'=>false,'errors'=>$validator->errors()]);
            }
            $data = $req->header;
            unset($data['uuid_supplier'], $data['uuid_lokasi'], $data['uuid_siklus'], $data['id_pakan']);
            $data['supplier_id'] = $supplier->id_supplier;
            $data['lokasi_id']   = $lokasi->id_lokasi;
            $data['siklus_id']   = $siklus->id_siklus;
            $insert = PembelianPakan::create($data);
            // insert biaya
            // $transBiaya = TransaksiBiaya::create([
            //     'no_transaksi'      => $data['no_pembelian'],
            //     'tanggal_transaksi' => $data['tanggal_pembelian'],
            //     'tanggal_mulai'     => $data['tanggal_pembelian'],
            //     'tanggal_selesai'   => $data['tanggal_pembelian'],
            //     'biaya_id'          => 1,
            //     'nominal'           => $data['total_nominal_netto'],
            //     'coa_id'            => 2,
            //     'keterangan'        => 'transaksi penaburan benur',
            //     'reff_id'           => $insert->id_pembelian,
            //     'reff_trans'        => 'penaburan_benur'
            // ]);
            // $transSiklus = TransaksiBiayaSiklus::create([
            //     'trans_biaya_id'=>$transBiaya->id,
            //     'siklus_id'     =>$siklus->id_siklus,
            // ]);
            foreach($req->detail as $d){
                $d['id_pembelian'] = $insert->id_pembelian;
                unset($d['kode_pakan'], $d['nama_pakan']);
                PembelianPakanDetail::create($d);

                // Tambahkan stok masuk
                StokHelper::updateStok(
                    $d['id_pakan'],
                    $d['jumlah'],            // Positif karena pembelian
                    'Pembelian Pakan',
                    $data['no_pembelian'],
                    $data['lokasi_id'],
                    $insert->id_pembelian,
                    $data['tanggal_pembelian']
                );
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
            $penaburanBenur = penaburanBenurModel::where('uuid', $uuid)->firstOrFail();
            $po = PoModel::where('uuid',$req->uuid_po)->first();
            $lokasi = SetupLokasi::where('uuid',$req->uuid_lokasi)->first();
            $siklus = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
            $req->validate([
                'uuid_po' => 'required',
                'no_pembelian'    => 'required',
                'tanggal_pembelian'   => 'required',
            ]);
            $data = $req->all();
            unset($data['uuid_po']);
            unset($data['uuid_supplier']);
            unset($data['uuid_lokasi']);
            unset($data['uuid_siklus']);
            $data['id_po_benur']    = $po->id_po_benur;
            $data['id_lokasi']      = $lokasi->id_lokasi;
            $data['id_siklus']      = $siklus->id_siklus;
            $penaburanBenur->update($data);
            $delete_detail = penaburanBenurDetailModel::where('id_pembelian',$penaburanBenur->id_pembelian)->delete();
            // update transaksi biaya
            $transBiaya = TransaksiBiaya::where('reff_id', $penaburanBenur->id_pembelian)->firstOrFail();
            // if($transBiaya->validated_at != null){
            //     throw new \Exception('Transaksi Biaya benur sudah di validasi, data tidak bisa di ubah');
            // }
            $transBiaya->update([
                'no_transaksi'      => $data['no_pembelian'],
                'tanggal_transaksi' => $data['tanggal_pembelian'],
                'tanggal_mulai'     => $data['tanggal_pembelian'],
                'tanggal_selesai'   => $data['tanggal_pembelian'],
                'biaya_id'          => 1,
                'nominal'           => $data['total_nominal_netto'],
                'coa_id'            => 2,
                'keterangan'        => 'transaksi penaburan benur'
            ]);

            $transSiklus = TransaksiBiayaSiklus::where('trans_biaya_id',$transBiaya->id)->firstOrFail();
            $transSiklus->update([
                'siklus_id'     =>$siklus->id_siklus
            ]);
            $deleteBiayaPetak = TransaksiBiayaPetak::where('trans_biaya_id',$transBiaya->id)->delete();
            foreach($req->detail as $d){
                $benur = SetupBenur::where('uuid',$d['uuid_benur'])->first();
                $petak = SetupPetak::where('uuid',$d['uuid_petak'])->first();
                $detail = $d;
                $detail['id_po_benur']    = $po->id_po_benur;
                $detail['id_benur'] = $benur->id_benur;
                $detail['id_petak'] = $petak->id_petak;
                $detail['kode_supplier'] = $d['kode_benur'];
                $detail['id_pembelian'] = $penaburanBenur->id_pembelian;
                unset($d['uuid_benur']);
                unset($d['uuid_petak']);
                $insert_detail = penaburanBenurDetailModel::create($detail);
                $transPetak = TransaksiBiayaPetak::create([
                    'trans_biaya_id'        =>$transBiaya->id,
                    'trans_biaya_siklus_id' =>$transSiklus->id,
                    'petak_id'              =>$petak->id_petak,
                    'biaya_id'              =>1,
                    'luas'                  =>$petak->luas_petak,
                    'persentase'            =>100,
                    'nominal_petak'         =>$detail['subtotal_neto'],
                    'tanggal_mulai'         =>$data['tanggal_pembelian'],
                    'tanggal_selesai'       =>$data['tanggal_pembelian'],
                ]);
            }
            DB::commit();
            return response()->json(['success'=>true,'data'=>$penaburanBenur,'message'=>'']);
        }catch(\Exception $err) {
            DB::rollBack();
            // throw $err;
            return response()->json(['success'=>false,'message'=>$err->getMessage()]);
        }
    }

    public function trigger_transaksi_biaya($payload)
    {
        
    }

    public function destroy($uuid)
    {
        $benur = penaburanBenurModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }

    public function get_detail($uuid){
        $penaburan = penaburanBenurModel::where('uuid',$uuid)->first();
        $detail = penaburanBenurDetailModel::where('id_pembelian',$penaburan->id_pembelian)
            ->join('setup_benur','penaburan_benur_detail.id_benur','=','setup_benur.id_benur')
            ->join('setup_petak','penaburan_benur_detail.id_petak','=','setup_petak.id_petak')
            ->join('setup_blok','setup_petak.blok_id','=','setup_blok.id_blok')
            ->select([
                'setup_blok.nama_blok as blok','setup_petak.nama_petak as petak','setup_petak.uuid as uuid_petak','setup_benur.uuid as uuid_benur',
                'penaburan_benur_detail.kode_supplier as kode_benur','penaburan_benur_detail.jenis_benur',
                'harga_bruto','jumlah_bruto','subtotal_bruto',
                'harga_neto','jumlah_neto','subtotal_neto',
                'harga_actual','jumlah_actual','subtotal_actual',
            ])->get();
        return response()->json(['success'=>true,'data'=>$detail,'message'=>'']);
    }
}
