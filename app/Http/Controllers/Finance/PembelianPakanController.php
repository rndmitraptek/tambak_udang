<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\GeneradeNomorHelper;
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
use App\Models\Finance\HutangSupplierModel;
use App\Models\Finance\PoModel;
use App\Models\SetupCoa;

class PembelianPakanController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.pembelian_pakan.index');
    }

    public function datatable(Request $request)
    {
        $query = PembelianPakan::withTrashed()->with(['supplier','lokasi'])
            ->orderBy('id_pembelian', 'desc');

        return DataTables::of($query)
            ->addColumn('supplier', fn($row) => $row->supplier->nama_supplier ?? '-')
            ->addColumn('lokasi', fn($row) => $row->lokasi->nama_lokasi ?? '-')
            ->addColumn('siklus', fn($row) => $row->siklus->nama_siklus ?? '-')
            ->addColumn('status', fn($row) => $row->deleted_at!=null ?'Batal':'')
            ->addColumn('actions', function ($row) use ($request) {
                if ($row->deleted_at) {
                    return '
                    <a href="javascript:void(0)" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-info m-btn--icon m-btn--icon-only m-btn--pill btn-detail" 
                        data-uuid="'.$row->uuid.'"
                        title="Detail">
                        <i class="la la-eye"></i>
                    </a>';
                } else {
                    return '
                    <a href="javascript:void(0)" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-info m-btn--icon m-btn--icon-only m-btn--pill btn-detail" 
                        data-uuid="'.$row->uuid.'"
                        title="Detail">
                        <i class="la la-eye"></i>
                    </a>
                    
                    <a href="javascript:void(0)" 
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill btn-batal" data-uuid="'.$row->uuid.'" 
                        title="Hapus">
                        <i class="m--font-danger la la-remove"></i>
                    </a>';
                }
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
            $data['no_pembelian'] = GeneradeNomorHelper::long_update('pembelian_pakan');
            unset($data['uuid_supplier'], $data['uuid_lokasi'], $data['uuid_siklus'], $data['id_pakan']);
            $data['supplier_id'] = $supplier->id_supplier;
            $data['lokasi_id']   = $lokasi->id_lokasi;
            $data['siklus_id']   = $siklus->id_siklus;
            $coa = SetupCoa::where('id_coa',$req->id_coa)->first();
            $data['kode_coa'] = $coa->kode_coa;
            $insert = PembelianPakan::create($data);

            //insert hutang supplier (kredit)
            if (!empty($data['is_hutang']) && $data['is_hutang'] == 1) {
                HutangSupplierModel::create([
                    'no_faktur' => $data['no_pembelian'],
                    'tanggal_hutang' => $data['tanggal_pembelian'],
                    'tanggal_jatuh_tempo' => $data['tanggal_pembelian'],
                    'id_supplier' => $supplier->id_supplier,
                    'reff_id' => $insert->id_pembelian,
                    'reff_trans' => 'PEMBELIAN_PAKAN',
                    'jumlah_hutang' => $data['total'],
                    'dibayar' => 0,
                    'sisa' => $data['total'],
                ]);
            }

            foreach($req->detail as $d){
                $d['id_pembelian'] = $insert->id_pembelian;
                unset($d['kode_pakan'], $d['nama_pakan']);
                PembelianPakanDetail::create($d);

                //update harga pakan terbaru
                $pakan = SetupPakan::where('id_pakan', $d['id_pakan'])->firstOrFail();
                $pakan->update([
                    'harga_pakan' => $d['harga']
                ]);

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

    public function batal($uuid)
    {
        DB::beginTransaction();
        try {
            $pembelian = PembelianPakan::with('detail')->where('uuid',$uuid)->firstOrFail();

            foreach($pembelian->detail as $item) {
                StokHelper::batalTransaksi(
                    $item->id_pakan,
                    $item->jumlah,
                    $pembelian->no_pembelian,
                    $pembelian->lokasi_id,
                    $item->id_pembelian,
                    'keluar'
                );
            }

            // Hapus hutang supplier terkait pembelian ini
            HutangSupplierModel::where('reff_id', $pembelian->id_pembelian)->where('reff_trans', 'PEMBELIAN_PAKAN')->delete();

            $pembelian->delete(); // jika ingin hapus record pembelian

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan dan stok dikembalikan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan transaksi: ' . $e->getMessage()
            ]);
        }
    }

    public function detail($uuid)
    {
        // Ambil transaksi termasuk soft deleted
        $transaksi = PembelianPakan::withTrashed()
            ->with(['detail.pakan', 'supplier', 'lokasi', 'siklus'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    public function get_coa(){
        $data = SetupCoa::whereRaw("LEFT(kode_coa, 3) in ('112','111') AND RIGHT(kode_coa, 1) <> '0'")->get();
        return response()->json(['success' => true, 'data' => $data]);
    }
}
