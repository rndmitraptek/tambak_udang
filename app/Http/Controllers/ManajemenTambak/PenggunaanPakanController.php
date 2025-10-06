<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use App\Models\Finance\PoModel;
use App\Models\ManajemenTambak\PenggunaanPakanDetail;
use App\Models\ManajemenTambak\PenggunaanPakan;
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

class PenggunaanPakanController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.penggunaan-pakan.index');
    }

    public function datatable()
    {
        $query = PenggunaanPakan::withTrashed()
            ->join('setup_siklus', 'penggunaan_pakan.siklus_id', '=', 'setup_siklus.id_siklus')
            ->join('setup_lokasi','setup_lokasi.id_lokasi','=','penggunaan_pakan.lokasi_id')
            ->select([
                'penggunaan_pakan.id_penggunaan','penggunaan_pakan.uuid', 
                'penggunaan_pakan.no_penggunaan', 'penggunaan_pakan.tanggal_penggunaan',
                'penggunaan_pakan.waktu','penggunaan_pakan.jumlah_petak','penggunaan_pakan.total',
                'keterangan','penggunaan_pakan.deleted_at',
                'setup_lokasi.nama_lokasi',
                'setup_siklus.nama_siklus',
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

    public function get_pakan($uuid_lokasi){
        $lokasi = SetupLokasi::where('uuid',$uuid_lokasi)->first();
        if($lokasi){
            $data = SetupPakan::where('lokasi_id',$lokasi->id_lokasi)
            ->leftjoin('stok_pakan','stok_pakan.pakan_id','=','setup_pakan.id_pakan')
            ->select(['setup_pakan.id_pakan','setup_pakan.uuid as uuid','nama_pakan','kode_pakan','stok_pakan.stok','harga_pakan as harga'])->get();
            return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
        } else{
            return response()->json(['success'=>false,'data'=>null,'message'=>'ERROR!, uuid lokasi tidak di temukan']);
        }
    }


    public function get_petak($uuid_siklus){
        $siklus = SetupSiklus::where('uuid',$uuid_siklus)->first();
        $data = DB::select("SELECT sp.id_petak,sp.uuid,sb.nama_blok as blok, sp.nama_petak as petak,sp.luas_petak as luas FROM setup_blok sb 
                    inner join setup_petak sp on sb.id_blok=sp.blok_id
                    inner join setup_siklus_petak ssp on sp.id_petak=ssp.petak_id
                    where ssp.siklus_id = ? and sp.deleted_at is null",[$siklus->id_siklus]);
        return response()->json(['success'=>true,'data'=>$data,'message'=>'']);
    }

    public function insert(Request $req){
        DB::beginTransaction();
        try {
            $lokasi = SetupLokasi::where('uuid',$req->uuid_lokasi)->firstOrFail();
            $siklus = SetupSiklus::where('uuid',$req->uuid_siklus)->firstOrFail();
            $req->validate([
                'no_penggunaan'    => 'required',
                'tanggal_penggunaan'   => 'required',
                'waktu' => 'required',
            ]);
            $data = $req->all();
            unset($data['id_pakan']);
            unset($data['id_petak']);
            unset($data['uuid_lokasi']);
            unset($data['uuid_siklus']);
            $data['lokasi_id']      = $lokasi->id_lokasi;
            $data['siklus_id']      = $siklus->id_siklus;
            $insert = PenggunaanPakan::create($data);
            // insert biaya
            $biaya_id =2;
            $transBiaya = TransaksiBiaya::create([
                'no_transaksi'      => $data['no_penggunaan'],
                'tanggal_transaksi' => $data['tanggal_penggunaan'],
                'tanggal_mulai'     => $data['tanggal_penggunaan'],
                'tanggal_selesai'   => $data['tanggal_penggunaan'],
                'biaya_id'          => $biaya_id,
                'nominal'           => $data['total'],
                'coa_id'            => 2,
                'keterangan'        => 'transaksi penggunaan pakan',
                'reff_id'           => $insert->id_penggunaan,
                'reff_trans'        => 'penggunaan_pakan'
            ]);
            $transSiklus = TransaksiBiayaSiklus::create([
                'trans_biaya_id'=>$transBiaya->id,
                'siklus_id'     =>$siklus->id_siklus,
            ]);

            // insert detail
            foreach($req->detail as $d){
                $petak = SetupPetak::where('id_petak',$d['id_petak'])->first();
                $detail = $d;
                $detail['pakan_id'] = $detail['id_pakan'];
                $detail['petak_id'] = $detail['id_petak'];
                $detail['harga_per_kg'] = $detail['harga'];
                $detail['id_penggunaan'] = $insert->id_penggunaan;
                unset($d['kode_pakan']);
                unset($d['nama_pakan']);
                unset($d['nama_blok']);
                unset($d['nama_petak']);
                unset($d['harga']);
                $insert_detail = PenggunaanPakanDetail::create($detail);
                $transPetak = TransaksiBiayaPetak::create([
                    'trans_biaya_id'        =>$transBiaya->id,
                    'trans_biaya_siklus_id' =>$transSiklus->id,
                    'petak_id'              =>$petak->id_petak,
                    'biaya_id'              =>$biaya_id,
                    'luas'                  =>$petak->luas_petak,
                    'persentase'            =>100,
                    'nominal_petak'         =>$detail['subtotal'],
                    'tanggal_mulai'         =>$data['tanggal_penggunaan'],
                    'tanggal_selesai'       =>$data['tanggal_penggunaan'],
                ]);

                //Pengurangan stok pakan
                StokHelper::updateStok(
                    $d['id_pakan'],
                    -abs($d['jumlah']),            // Negatif karena penggunaan
                    'Penggunaan Pakan',
                    $data['no_penggunaan'],
                    $lokasi->id_lokasi,
                    $insert->id_penggunaan,
                    $data['tanggal_penggunaan']
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
            $pembelian = PenggunaanPakan::with('detail')->where('uuid',$uuid)->firstOrFail();

            // delete transaksi biaya terkait
            TransaksiBiaya::where('reff_id',$pembelian->id_penggunaan)->where('reff_trans','penggunaan_pakan')->delete();
            
            // kembalikan stok pakan
            foreach($pembelian->detail as $item) {
                StokHelper::batalTransaksi(
                    $item->pakan_id,
                    $item->jumlah,
                    $pembelian->no_penggunaan,
                    $pembelian->lokasi_id,
                    $item->id_penggunaan
                );
            }

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
    

    public function get_detail($uuid){
        $penggunaan = PenggunaanPakan::withTrashed()->where('uuid',$uuid)->firstOrFail();
        $detail = PenggunaanPakanDetail::where('penggunaan_pakan_detail.id_penggunaan',$penggunaan->id_penggunaan)
            ->join('penggunaan_pakan','penggunaan_pakan_detail.id_penggunaan','=','penggunaan_pakan.id_penggunaan')
            ->join('setup_petak','penggunaan_pakan_detail.petak_id','=','setup_petak.id_petak')
            ->join('setup_pakan','penggunaan_pakan_detail.pakan_id','=','setup_pakan.id_pakan')
            ->join('setup_blok','setup_petak.blok_id','=','setup_blok.id_blok')
            ->select([
                'no_penggunaan','setup_blok.nama_blok','setup_petak.nama_petak',
                'harga_per_kg','jumlah','subtotal','setup_pakan.nama_pakan','setup_pakan.kode_pakan'
            ])->get();
        return response()->json(['success'=>true,'data'=>$detail,'message'=>'']);
    }
}
