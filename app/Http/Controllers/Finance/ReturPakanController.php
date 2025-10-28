<?php

namespace App\Http\Controllers\Finance;

use App\Helpers\GeneradeNomorHelper;
use App\Http\Controllers\Controller;
use App\Models\Finance\PembelianPakan;
use App\Models\Finance\ReturPakanDetail;
use App\Models\Finance\ReturPakan;
use App\Models\Finance\PiutangSupplier;
use App\Models\ManajemenTambak\TransaksiBiaya;
use App\Models\ManajemenTambak\TransaksiBiayaPetak;
use App\Models\ManajemenTambak\TransaksiBiayaSiklus;
use App\Models\SetupBenur;
use App\Models\SetupLokasi;
use App\Models\SetupPetak;
use App\Models\SetupSiklus;
use App\Models\SetupPakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\StokHelper;

class ReturPakanController extends Controller
{
    //
    public function index()
    {
        return view('feature.finance.retur-pakan.index');
    }

    public function datatable()
    {
        $query = ReturPakan::withTrashed()
            ->join('pembelian_pakan', 'pembelian_pakan.id_pembelian', '=', 'retur_pakan.pembelian_id')
            ->join('setup_siklus', 'pembelian_pakan.siklus_id', '=', 'setup_siklus.id_siklus')
            ->join('setup_lokasi','setup_lokasi.id_lokasi','=','pembelian_pakan.lokasi_id')
            ->select([
                'retur_pakan.uuid', 'retur_pakan.no_retur', 'retur_pakan.total', 'retur_pakan.keterangan','retur_pakan.tanggal_retur','retur_pakan.deleted_at'
                ,'pembelian_pakan.no_pembelian','setup_lokasi.uuid as uuid_lokasi','setup_lokasi.nama_lokasi','setup_siklus.uuid as uuid_siklus','setup_siklus.nama_siklus',
                'retur_pakan.created_by','retur_pakan.updated_by','retur_pakan.created_at','retur_pakan.updated_at'
            ]);
        return DataTables::of($query)
            ->addColumn('status', fn($row) => $row->deleted_at!=null ?'Batal':'')
            ->addColumn('action', function ($row) {
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
            ->rawColumns(['action'])
            ->make(true);
    }

    public function lokasi(){
        $data = SetupLokasi::all()->makeHidden(['id_lokasi']);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_siklus()
    {
        $data = SetupSiklus::where('status','OPEN')
        ->join('setup_lokasi','setup_siklus.id_lokasi','=','setup_lokasi.id')
        ->select(['setup_lokasi.nama as lokasi','setup_siklus.uuid','setup_siklus.nama'])->get();
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function get_pembelian(Request $request){
        $query = PembelianPakan::query()
            ->join('setup_lokasi', 'pembelian_pakan.lokasi_id', '=', 'setup_lokasi.id_lokasi')
            ->join('setup_supplier', 'setup_supplier.id_supplier', '=', 'pembelian_pakan.supplier_id')
            ->join('setup_siklus', 'setup_siklus.id_siklus', '=', 'pembelian_pakan.siklus_id')
            ->select([
                'pembelian_pakan.uuid', 'pembelian_pakan.no_pembelian', 'pembelian_pakan.tanggal_pembelian','pembelian_pakan.total',
                'setup_supplier.nama_supplier','setup_supplier.uuid as uuid_supplier',
                'setup_lokasi.uuid as uuid_lokasi','setup_lokasi.nama_lokasi',
                'setup_siklus.uuid as uuid_siklus','setup_siklus.nama_siklus',
            ]);
        if ($request->has('textSearch') && $request->textSearch != '') {
            $text = strtoupper($request->textSearch);
            $query->where(DB::raw('UPPER(no_pembelian)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(nama_supplier)'), 'like', "%{$text}%");
            $query->orWhere(DB::raw('UPPER(nama_lokasi)'), 'like', "%{$text}%");
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

    public function get_pembelian_detail($uuid_pembelian){
        $pembelian = PembelianPakan::where('uuid',$uuid_pembelian)->firstOrFail();
        $data = DB::select("SELECT false as checked, sp.uuid as uuid_pakan,sp.nama_pakan,
            sp.jenis_pakan,sp.kode_pakan, s.stok as jumlah, ppd.harga, ppd.subtotal
                    FROM pembelian_pakan_detail ppd
                    inner join setup_pakan sp on ppd.id_pakan=sp.id_pakan
                    inner join stok_pakan s on ppd.id_pakan=s.pakan_id AND s.lokasi_id=?
                    where ppd.id_pembelian = ? and sp.deleted_at is null",[$pembelian->lokasi_id, $pembelian->id_pembelian]);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function insert(Request $req){
        DB::beginTransaction();
        try {
            $pembelian = PembelianPakan::where('uuid',$req->uuid_pembelian)->first();
            $lokasi = SetupLokasi::where('uuid',$req->uuid_lokasi)->first();
            $siklus = SetupSiklus::where('uuid',$req->uuid_siklus)->first();
            $req->validate([
                'uuid_pembelian' => 'required',
                'no_retur'    => 'required',
                'tanggal_retur'   => 'required',
            ]);
            $data = $req->all();
            $data['no_retur'] = GeneradeNomorHelper::long_update('retur_pakan');
            unset($data['uuid_pembelian']);
            unset($data['uuid_supplier']);
            unset($data['uuid_lokasi']);
            unset($data['uuid_siklus']);
            $data['pembelian_id']    = $pembelian->id_pembelian;
            $data['lokasi_id']      = $lokasi->id_lokasi;
            $data['siklus_id']      = $siklus->id_siklus;
            $insert = ReturPakan::create($data);

            //insert piutang supplier (kredit)
            PiutangSupplier::create([
                'no_faktur' => $data['no_retur'],
                'tanggal_piutang' => $data['tanggal_retur'],
                'tanggal_jatuh_tempo' => $data['tanggal_retur'],
                'id_supplier' => $pembelian->supplier_id,
                'reff_id' => $insert->id_retur,
                'reff_trans' => 'RETUR_PAKAN',
                'jumlah_piutang' => $data['total'],
                'dibayar' => 0,
                'sisa' => $data['total'],
            ]);
            
            foreach($req->detail as $d){
                $pakan = SetupPakan::where('uuid',$d['uuid_pakan'])->first();
                $detail = $d;
                $detail['pakan_id'] = $pakan->id_pakan;
                $detail['id_retur'] = $insert->id_retur;
                unset($detail['uuid_pakan']);
                $insert_detail = ReturPakanDetail::create($detail);

                //Pengurangan stok pakan
                StokHelper::updateStok(
                    $pakan->id_pakan,
                    -abs($d['jumlah_retur']),            // Negatif karena retur
                    'Retur Pakan',
                    $data['no_retur'],
                    $lokasi->id_lokasi,
                    $insert->id_retur,
                    $data['tanggal_retur']
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
            $retur = ReturPakan::with('detail')->where('uuid',$uuid)->firstOrFail();
            
            // kembalikan stok pakan
            foreach($retur->detail as $item) {
                StokHelper::batalTransaksi(
                    $item->pakan_id,
                    $item->jumlah_retur,
                    $retur->no_retur,
                    $retur->lokasi_id,
                    $item->id_retur,
                    'masuk'
                );
            }

            // Hapus piutang supplier terkait retur ini
            PiutangSupplier::where('reff_id', $retur->id_retur)->where('reff_trans', 'RETUR_PAKAN')->delete();

            $retur->delete(); // jika ingin hapus record retur

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
    

    public function get_detail($uuid){
        $retur = ReturPakan::withTrashed()->where('uuid',$uuid)->firstOrFail();
        $detail = ReturPakanDetail::where('retur_pakan_detail.id_retur',$retur->id_retur)
            ->join('retur_pakan','retur_pakan_detail.id_retur','=','retur_pakan.id_retur')
            ->join('setup_pakan','retur_pakan_detail.pakan_id','=','setup_pakan.id_pakan')
            ->select([
                'no_retur','tanggal_retur','retur_pakan.keterangan','retur_pakan.total',
                'harga_per_kg','jumlah_retur','subtotal','setup_pakan.nama_pakan','setup_pakan.kode_pakan'
            ])->get();
        return response()->json(['success'=>true,'data'=>$detail,'message'=>'']);
    }
}
