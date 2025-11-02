<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\HutangSupplierModel;
use App\Models\Finance\PembelianBarangDetailModel;
use App\Models\Finance\PembelianBarangModel;
use App\Models\SetupBarang;
use App\Models\SetupCoa;
use App\Models\SetupLokasi;
use App\Models\SetupSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PembelianBarangController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.pembelian-barang.index');
    }

    public function datatable()
    {
        $query = PembelianBarangModel::query()
        ->join('setup_lokasi', 'pembelian_barang.id_lokasi', '=', 'setup_lokasi.id_lokasi')
        ->join('setup_supplier', 'pembelian_barang.id_supplier', '=', 'setup_supplier.id_supplier')
        ->select(['pembelian_barang.uuid','pembelian_barang.no_pembelian_barang','pembelian_barang.tanggal_pembelian_barang','pembelian_barang.tanggal_jatuh_tempo','pembelian_barang.id_lokasi','pembelian_barang.id_supplier','pembelian_barang.jumlah','pembelian_barang.total','pembelian_barang.pembayaran','pembelian_barang.keterangan',
            'setup_lokasi.nama_lokasi', 'setup_lokasi.uuid as uuid_lokasi',
            'setup_supplier.uuid as uuid_supplier','setup_supplier.nama_supplier',
            'pembelian_barang.created_by','pembelian_barang.updated_by','pembelian_barang.created_at','pembelian_barang.updated_at'
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

    public function barang(Request $request){
        $query = SetupBarang::select(['uuid','nama_barang','harga']);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(nama_barang)'), 'like', "%{$text}%");
        }
        return DataTables::of($query)->make(true);
    }

    public function insert(Request $req){
        DB::beginTransaction();
        try {
            $lokasi = SetupLokasi::where('uuid',$req->uuid_lokasi)->first();
            $supplier = SetupSupplier::where('uuid',$req->uuid_supplier)->first();
            $req->validate([
                'uuid_lokasi' => 'required',
                'uuid_supplier'    => 'required',
                'tanggal_pembelian_barang'   => 'required',
            ]);
            $data = $req->all();
            $coa = SetupCoa::where('id_coa',$req->id_coa)->first();
            $data['kode_coa'] = $coa->kode_coa;
            $data['id_lokasi']      = $lokasi->id_lokasi;
            $data['id_supplier']      = $supplier->id_supplier;
            $insert = PembelianBarangModel::create($data);
            foreach($req->detail as $d){
                $barang = SetupBarang::where('uuid',$d['uuid_barang'])->first();
                $detail = $d;
                $detail['id_barang']    = $barang->id_barang;
                $detail['id_pembelian_barang'] = $insert->id_pembelian_barang;
                $insert_detail = PembelianBarangDetailModel::create($detail);
            }
            // insert hutang supplier
            $insert_hutang_supplier = HutangSupplierModel::create([
                'id_supplier'           =>$supplier->id_supplier,
                'no_faktur'             =>$data['no_pembelian_barang'],
                'reff_id'               =>$insert->id_pembelian_barang,
                'reff_trans'            =>'PEMBELIAN BARANG',
                'tanggal_hutang'        =>$data['tanggal_pembelian_barang'],
                'tanggal_jatuh_tempo'   =>$data['tanggal_jatuh_tempo'],
                'jumlah_hutang'         =>$data['total'],
                'dibayar'               =>0,
                'sisa'                  =>$data['total']
            ]);
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
            $pembelianBarang = PembelianBarangModel::where('uuid', $uuid)->firstOrFail();
            $lokasi = SetupLokasi::where('uuid',$req->uuid_lokasi)->first();
            $supplier = SetupSupplier::where('uuid',$req->uuid_supplier)->first();
            $req->validate([
                'uuid_lokasi' => 'required',
                'uuid_supplier'    => 'required',
                'tanggal_pembelian_barang'   => 'required',
            ]);
            $data = $req->all();
            $coa = SetupCoa::where('id_coa',$req->id_coa)->first();
            $data['kode_coa'] = $coa->kode_coa;
            $data['id_lokasi']      = $lokasi->id_lokasi;
            $data['id_supplier']      = $supplier->id_supplier;
            $pembelianBarang->update($data);
            $delete_detail = PembelianBarangDetailModel::where('id_pembelian_barang',$pembelianBarang->id_pembelian_barang)->delete();
            // update hutang supplier
            $hutangSupplier = HutangSupplierModel::where('reff_id', $pembelianBarang->id_pembelian_barang)->delete();

            foreach($req->detail as $d){
                $barang = SetupBarang::where('uuid',$d['uuid_barang'])->first();
                $detail = $d;
                $detail['id_barang']    = $barang->id_barang;
                $detail['id_pembelian_barang'] = $pembelianBarang->id_pembelian_barang;
                $insert_detail = PembelianBarangDetailModel::create($detail);
            }
            // insert hutang supplier
            $insert_hutang_supplier = HutangSupplierModel::create([
                'id_supplier'           =>$supplier->id_supplier,
                'no_faktur'             =>$data['no_pembelian_barang'],
                'reff_id'               =>$pembelianBarang->id_pembelian_barang,
                'reff_trans'            =>'PEMBELIAN BARANG',
                'tanggal_hutang'        =>$data['tanggal_pembelian_barang'],
                'tanggal_jatuh_tempo'   =>$data['tanggal_jatuh_tempo'],
                'jumlah_hutang'         =>$data['total'],
                'dibayar'               =>0,
                'sisa'                  =>$data['total']
            ]);
            DB::commit();
            return response()->json(['success'=>true,'data'=>$pembelianBarang,'message'=>'']);
        }catch(\Exception $err) {
            DB::rollBack();
            // throw $err;
            return response()->json(['success'=>false,'message'=>$err->getMessage()]);
        }
    }

    public function destroy($uuid)
    {
        $benur = PembelianBarangModel::where('uuid', $uuid)->firstOrFail();
        $benur->delete();
        return response()->json(['success' => true]);
    }

    public function get_detail($uuid)
    {
        $pembelian_barang = PembelianBarangModel::where('uuid',$uuid)->first();
        $detail = PembelianBarangDetailModel::where('id_pembelian_barang',$pembelian_barang->id_pembelian_barang)
            ->join('setup_barang','setup_barang.id_barang','=','pembelian_barang_detail.id_barang')
            ->select([
                'pembelian_barang_detail.id_barang',
                'setup_barang.uuid as uuid_barang',
                'setup_barang.nama_barang',
                'pembelian_barang_detail.harga',
                'pembelian_barang_detail.qty',
                'pembelian_barang_detail.subtotal',
            ])->get();
        return response()->json(['success'=>true,'data'=>$detail,'message'=>'']);
    }

    public function get_coa(){
        $data = SetupCoa::whereRaw("LEFT(kode_coa, 3) in ('112','111') AND RIGHT(kode_coa, 1) <> '0'")->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

}
