<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManajemenTambak\TransaksiSimulasi;
use App\Models\ManajemenTambak\TransaksiSimulasiBiaya;
use App\Models\ManajemenTambak\TransaksiSimulasiDetail;
use App\Models\ManajemenTambak\PanenModel;
use App\Models\ManajemenTambak\PenggunaanPakan;
use App\Models\ManajemenTambak\PenggunaanPakanDetail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SetupLokasi;
use App\Models\SetupSiklus;
use App\Models\SetupSiklusPetak;
use Illuminate\Support\Facades\Auth;

class SimulasiController extends Controller
{
    //
    public function index()
    {
        $isDashboard=false;
        $user = Auth::user();
        $getRoles = DB::table('role_user')
                ->join('role','role.id_role','role_user.id_role')
                ->select('role.*')
                ->where('role_user.id_user', $user->id_user)->get();

        foreach ($getRoles as $role) {
            if (!empty($role->dashboard) && $role->dashboard == true) {
                $isDashboard = true;
                break; // cukup satu yang true sudah cukup
            }
        }

        return view('feature.manajemen-tambak.simulasi.index', compact('isDashboard'));
    }

    public function lokasi()
    {
        // ambil semua lokasi
        return response()->json(
            SetupLokasi::select('id_lokasi', 'nama_lokasi')->get()
        );
    }

    public function siklusByLokasi($lokasiId)
    {
        // filter siklus sesuai lokasi_id
        $siklus = SetupSiklus::where('lokasi_id', $lokasiId)
            ->select('id_siklus', 'nama_siklus')
            ->get();

        return response()->json($siklus);
    }

    // public function petakBySiklus($siklusId)
    // {
    //     $petak = SetupSiklusPetak::with(['petak'])->where('siklus_id', $siklusId)->orderBy('id_siklus_petak','asc')
    //               ->get();

    //     return response()->json($petak);
    // }
    public function petakBySiklus($siklusId, $tanggalSimulasi)
    {
        $tglSimulasi = Carbon::parse($tanggalSimulasi);

        // Ambil tanggal mulai siklus
        $siklus = DB::table('setup_siklus')->where('id_siklus', $siklusId)->first();
        $tglMulaiSiklus = $siklus ? Carbon::parse($siklus->tanggal_mulai) : null;

        $petakList = SetupSiklusPetak::with(['petak' => function($q) {
                $q->whereNull('deleted_at');
            }, 'petak.blok'])
            ->where('siklus_id', $siklusId)
            ->whereHas('petak', function($q) {
                $q->whereNull('deleted_at');
            })
            ->orderBy('id_siklus_petak', 'asc')
            ->get();

        foreach ($petakList as $petakItem) {
            $petakId = $petakItem->petak_id;

            // ambil id benur dari penamburan benur per petak
            $penaburanBenurDetail = DB::table('penaburan_benur_detail')
                ->join('penaburan_benur','penaburan_benur.id_penaburan_benur','penaburan_benur_detail.id_penaburan_benur')
                ->where('penaburan_benur.id_siklus', $siklusId)
                ->where('penaburan_benur_detail.id_petak', $petakId)
                ->orderBy('id_penaburan_benur_detail','desc')->first();
            $petakItem->benur_id =$penaburanBenurDetail ? $penaburanBenurDetail->id_benur :null;
            $petakItem->jenis_benur =$penaburanBenurDetail ? $penaburanBenurDetail->jenis_benur :null;
            $petakItem->jumlah_benur =$penaburanBenurDetail ? $penaburanBenurDetail->jumlah_neto :null;
            $petakItem->doc =$penaburanBenurDetail ?  Carbon::parse($penaburanBenurDetail->tanggal_penaburan)->diffInDays($tglSimulasi) + 1 :null;

            // Query 1: biaya selesai sebelum simulasi
            $biayaLaluRows = DB::table('transaksi_biaya_petak as tbp')
                ->join('setup_biaya as sb','tbp.biaya_id','=','sb.id_biaya')
                ->leftJoin('setup_coa as sc','sb.coa_id','=','sc.id_coa')
                ->join('transaksi_biaya as tb','tbp.trans_biaya_id','=','tb.id')
                ->join('transaksi_biaya_siklus as tbs','tbp.trans_biaya_siklus_id','=','tbs.id')
                ->select('tbp.*','tb.no_transaksi','sb.nama_biaya','sb.coa_id','sc.nama_coa','sc.kode_coa')
                ->where('tbp.petak_id', $petakId)
                ->where('tbs.siklus_id', $siklusId)
                ->whereNull('tb.deleted_at')
                // mulai biaya >= mulai siklus
                ->when($tglMulaiSiklus, function($q) use ($tglMulaiSiklus){
                    $q->where('tbp.tanggal_mulai','>=',$tglMulaiSiklus);
                })
                ->where('tbp.tanggal_mulai','<=',$tglSimulasi)
                ->where('tbp.tanggal_selesai','<=',$tglSimulasi)
                ->get();

            $totalBiayaLalu = $biayaLaluRows->sum('nominal_petak');

            // Query 2: biaya berjalan saat simulasi
            $biayaBerjalanRows = DB::table('transaksi_biaya_petak as tbp')
                ->join('setup_biaya as sb','tbp.biaya_id','=','sb.id_biaya')
                ->leftJoin('setup_coa as sc','sb.coa_id','=','sc.id_coa')
                ->join('transaksi_biaya as tb','tbp.trans_biaya_id','=','tb.id')
                ->join('transaksi_biaya_siklus as tbs','tbp.trans_biaya_siklus_id','=','tbs.id')
                ->select('tbp.*','tb.no_transaksi','sb.nama_biaya','sb.coa_id','sc.nama_coa','sc.kode_coa')
                ->where('tbp.petak_id', $petakId)
                ->where('tbs.siklus_id', $siklusId)
                ->when($tglMulaiSiklus, function($q) use ($tglMulaiSiklus){
                    $q->where('tbp.tanggal_mulai','>=',$tglMulaiSiklus);
                })
                ->where('tbp.tanggal_mulai','<',$tglSimulasi)
                ->where('tbp.tanggal_selesai','>',$tglSimulasi)
                ->get();

            $totalProrata = 0;
            $detailAll = [];

            // biaya lalu
            foreach ($biayaLaluRows as $row) {
                $detailAll[] = [
                    'id_transaksi_biaya_petak' => $row->id,
                    'no_transaksi' => $row->no_transaksi,
                    'coa_id' => $row->coa_id,
                    'nama_coa' => $row->nama_coa,
                    'kode_coa' => $row->kode_coa,
                    'nama_biaya' => $row->nama_biaya,
                    'tanggal_mulai' => $row->tanggal_mulai,
                    'tanggal_selesai' => $row->tanggal_selesai,
                    'nominal_petak' => (float)$row->nominal_petak,
                    'tipe_perhitungan' => 'full',
                    'biaya_hitung' => round($row->nominal_petak, 0)
                ];
            }

            // biaya prorata
            foreach ($biayaBerjalanRows as $row) {
                $tglMulai = Carbon::parse($row->tanggal_mulai);
                $tglSelesai = Carbon::parse($row->tanggal_selesai);

                $totalHari = $tglMulai->diffInDays($tglSelesai) + 1;
                $hariSampaiSimulasi = $tglMulai->diffInDays($tglSimulasi) + 1;
                $biayaPerHari = $row->nominal_petak / $totalHari;
                $biayaReal = $biayaPerHari * $hariSampaiSimulasi;

                $totalProrata += $biayaReal;

                $detailAll[] = [
                    'id_transaksi_biaya_petak' => $row->id,
                    'no_transaksi' => $row->no_transaksi,
                    'coa_id' => $row->coa_id,
                    'nama_coa' => $row->nama_coa,
                    'kode_coa' => $row->kode_coa,
                    'nama_biaya' => $row->nama_biaya,
                    'tanggal_mulai' => $row->tanggal_mulai,
                    'tanggal_selesai' => $row->tanggal_selesai,
                    'nominal_petak' => (float)$row->nominal_petak,
                    'tipe_perhitungan' => 'prorata',
                    'total_hari' => $totalHari,
                    'biaya_per_hari' => round($biayaPerHari,2),
                    'hari_sampai_simulasi' => $hariSampaiSimulasi,
                    'biaya_hitung' => round($biayaReal, 0)
                ];
            }

            $totalBiayaSimulasi = $totalBiayaLalu + $totalProrata;

            $petakItem->biaya_simulasi = round($totalBiayaSimulasi,0);
            $petakItem->detail_biaya = $detailAll;
        }

        return response()->json($petakList);
    }

    public function data(Request $request)
    {
        $query = TransaksiSimulasi::with(['siklus', 'lokasi'])->orderBy('id_simulasi','desc');

        return DataTables::eloquent($query)
            ->addColumn('nama_siklus', function ($row) {
                return optional($row->siklus)->nama_siklus; // sesuaikan field di SetupSiklus
            })
            ->addColumn('nama_lokasi', function ($row) {
                return optional($row->lokasi)->nama_lokasi; // sesuaikan field di SetupLokasi
            })
            ->toJson();
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Simpan transaksi_simulasi
            $simulasi = TransaksiSimulasi::create([
                'siklus_id' => $request->input('siklus_id'),
                'lokasi_id' => $request->input('lokasi_id'),
                'tanggal_simulasi' => $request->input('tanggal_simulasi'),
                'catatan' => $request->input('catatan'),
            ]);

            // Loop petakList untuk simpan biaya per petak
            foreach ($request->input('petakList') as $petak) {
                // petak['detail_biaya'] adalah json detail dari query sebelumnya
                // foreach ($petak['detail_biaya'] as $detail) {
                    TransaksiSimulasiBiaya::create([
                        'trans_simulasi_id' => $simulasi->id_simulasi,
                        'petak_id' => $petak['petak_id'],
                        'benur_id' => $petak['benur_id'],
                        'luas_petak' => $petak['petak']['luas_petak'],
                        'nominal_biaya' => $petak['biaya_simulasi'],
                        'jenis_benur' => $petak['jenis_benur'],
                        'jumlah_benur' => $petak['jumlah_benur'],
                        'doc' => $petak['doc'],
                        'detail_biaya_actual' => json_encode($petak['detail_biaya']),
                    ]);
                // }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Simulasi tersimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($uuid)
    {
        $simulasi = TransaksiSimulasi::with(['siklus','biaya.petak.blok','pendapatan.petak.blok'])
            ->where('uuid',$uuid)
            ->firstOrFail();

        $result = [];

        // --- Ambil biaya simulasi dari function yang sudah ada ---
        $biayaSimulasiList = $this->getBiayaSimulasiRaw($simulasi->id_simulasi); 
        // -> kita buat versi raw supaya return array, bukan response()->json

        $mapBiayaSimulasi = collect($biayaSimulasiList)->keyBy('petak_id'); 

        // karena biaya & pendapatan sudah total per petak, cukup pakai nilai langsung
        // misal di tabel transaksi_simulasi_biaya sudah ada kolom total per petak
        // misal di tabel transaksi_simulasi_pendapatan juga ada kolom total per petak
        $petakIds = collect($simulasi->biaya)->pluck('petak_id')
            ->merge(collect($simulasi->pendapatan)->pluck('petak_id'))
            ->unique();

        // get total pakan per petak
        $pakanTotals = DB::table('penggunaan_pakan_detail as d')
            ->join('penggunaan_pakan as p', 'p.id_penggunaan', '=', 'd.id_penggunaan')
            ->whereNull('p.deleted_at')
            ->where('p.lokasi_id', $simulasi->lokasi_id)
            ->where('p.siklus_id', $simulasi->siklus_id)
            ->where('p.tanggal_penggunaan','<=', $simulasi->tanggal_simulasi)
            ->select('d.petak_id', DB::raw('SUM(d.jumlah) as total_jumlah'))
            ->groupBy('d.petak_id')
            ->pluck('total_jumlah', 'd.petak_id'); 

        foreach ($petakIds as $petakId) {
            // total biaya & pendapatan actual sudah langsung
            $totalBiaya = $simulasi->biaya
                ->where('petak_id', $petakId)
                ->sum('nominal_biaya');

            $totalPendapatan = $simulasi->pendapatan
                ->where('petak_id', $petakId)
                ->sum('pendapatan_subtotal');
            
            // biaya simulasi (jika ada)
            $totalBiayaSimulasi = $mapBiayaSimulasi->has($petakId) 
                ? $mapBiayaSimulasi[$petakId]['biaya_simulasi'] 
                : 0;

            // laba rugi = pendapatan - biaya real - biaya simulasi
            $labaRugi = $totalPendapatan - ($totalBiaya + $totalBiayaSimulasi);
            // $labaRugi = $totalPendapatan - $totalBiaya;


            $detailBiaya = $simulasi->biaya
                ->where('petak_id', $petakId)
                ->first();
            $detailPendapatan = $simulasi->pendapatan()
                ->where('petak_id', $petakId)
                ->orderBy('petak_id','asc')
                ->first();

            //get panen
            $panen =PanenModel::where('id_petak',$petakId)->where('tanggal_panen','<=', $simulasi->tanggal_simulasi)->where('id_siklus',$simulasi->siklus_id)->sum('total');
            $totalPanen = $panen ? $panen : 0;
            if($detailPendapatan){
                $detailPendapatan->pendapatan_actual_partial =round($totalPanen,0);
            }

            // hitung hpp per kg
            $biomassa = isset($detailPendapatan['biomassa']) ? (float)$detailPendapatan['biomassa'] : 0;
            $totalBiayaAll = (float)$totalBiaya + (float)$totalBiayaSimulasi;
            $hppPerKg = 0;
            if ($biomassa > 0) {
                $hppPerKg = round($totalBiayaAll / $biomassa);
            }

            // ambil total pakan berdasarkan petak_id
            $totalPakanPetak = isset($pakanTotals[$petakId]) ? (float)$pakanTotals[$petakId] : 0;

            // hitung FCR, total pakan / biomassa (hindari pembagian 0)
            $fcr = $biomassa > 0 ? round($totalPakanPetak / $biomassa, 2) : 0;

            $result[] = [
                'petak_id' => $petakId,
                'total_biaya_real' => round($totalBiaya,0),
                'total_biaya_simulasi' => round($totalBiayaSimulasi,0),
                'total_biaya_all' => round($totalBiaya + $totalBiayaSimulasi,0),
                'total_pendapatan' => round($totalPendapatan,0),
                'pendapatan_actual_partial' => round($totalPanen,0),
                'laba_rugi' => round($labaRugi,0),
                'hpp_per_kg' => $hppPerKg,
                'total_pakan' => $totalPakanPetak,
                'biomassa' => $biomassa,
                'fcr' => $fcr,
                // jika mau sertakan detail json biaya:
                'detail_biaya' => $detailBiaya,
                'detail_pendapatan' => $detailPendapatan,
            ];
        }

        // tambahkan array ini ke response
        $simulasi->simulasi = $result;

        // total keseluruhan opsional
        $simulasi->total_biaya_semua_petak = round(collect($result)->sum('total_biaya'),0);
        $simulasi->total_pendapatan_semua_petak = round(collect($result)->sum('total_pendapatan'),0);
        $simulasi->total_laba_rugi_semua_petak = round(collect($result)->sum('laba_rugi'),0);

        // Simpan ke tabel transaksi_simulasi_detail
        $this->saveSimulasiDetail($simulasi, $result);

        return response()->json($simulasi);
    }


    protected function saveSimulasiDetail($simulasi, $result)
    {
        foreach ($result as $row) {
            TransaksiSimulasiDetail::updateOrCreate(
                [
                    'id_simulasi' => $simulasi->id_simulasi,
                    'petak_id' => $row['petak_id'],
                ],
                [
                    'tanggal_simulasi' => $simulasi->tanggal_simulasi,
                    'lokasi_id' => $simulasi->siklus->lokasi_id ?? null,
                    'siklus_id' => $simulasi->siklus_id,
                    'biomassa' => $row['detail_pendapatan']['biomassa'] ?? 0,
                    'harga_per_kg' => $row['detail_pendapatan']['harga_per_kg'] ?? 0,
                    'total_pendapatan' => $row['total_pendapatan'] ?? 0,
                    'total_biaya' => $row['total_biaya_all'] ?? 0,
                    'laba_rugi' => $row['laba_rugi'] ?? 0,
                    'hpp_per_kg' => $row['hpp_per_kg'] ?? 0,
                    'fcr' => $row['fcr'] ?? 0,
                    'doc' => $row['detail_biaya']['doc'] ?? 0,
                    'keterangan' => null,
                ]
            );
        }
    }


    public function pendapatan_save(Request $request)
    {
        $request->validate([
            'trans_simulasi_id' => 'required|integer',
            'items' => 'required|array',
        ]);

        $simulasiId = $request->input('trans_simulasi_id');
        $items = $request->input('items');

        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                // hitung subtotal pendapatan misal harga_per_kg * biomassa
                $pendapatanSubtotal = 0;
                $pendapatanSimulasi =(float)($item['pendapatan_simulasi'] ?? 0);
                $pendapatanActualPartial =(float)($item['pendapatan_actual_partial'] ?? 0);
                if (isset($item['harga_per_kg']) && isset($item['biomassa'])) {
                    $pendapatanSubtotal = $pendapatanSimulasi + $pendapatanActualPartial;
                }

                \App\Models\ManajemenTambak\TransaksiSimulasiPendapatan::updateOrCreate(
                    [
                        'trans_simulasi_id' => $simulasiId,
                        'petak_id' => $item['petak_id'], // wajib dikirim dari front end
                    ],
                    [
                        'harga_per_kg' => (float)($item['harga_per_kg'] ?? 0),
                        'biomassa' => (float)($item['biomassa'] ?? 0),
                        'pendapatan_simulasi' => (float)($item['pendapatan_simulasi'] ?? 0),
                        'pendapatan_actual_partial' => (float)($item['pendapatan_actual_partial'] ?? 0),
                        'pendapatan_subtotal' => round($pendapatanSubtotal,2),
                    ]
                );
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Pendapatan berhasil disimpan']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function form_biaya_simulasi($uuid_simulasi)
    {
        return view('feature.manajemen-tambak.simulasi.form_biaya_simulasi',compact('uuid_simulasi'));
    }

    private function getBiayaSimulasiRaw($simulasiId)
    {
        $getSimulasi = DB::table('transaksi_simulasi')->where('id_simulasi', $simulasiId)->first();
        if (!$getSimulasi) {
            return [];
        }

        $tglSimulasi = Carbon::parse($getSimulasi->tanggal_simulasi);
        $siklusId = $getSimulasi->siklus_id;

        // Ambil tanggal mulai siklus
        $siklus = DB::table('setup_siklus')->where('id_siklus', $siklusId)->first();
        $tglMulaiSiklus = $siklus ? Carbon::parse($siklus->tanggal_mulai) : null;

        $petakList = SetupSiklusPetak::with('petak.blok')
            ->where('siklus_id', $siklusId)
            ->orderBy('id_siklus_petak','asc')
            ->get();

        $result = [];

        foreach ($petakList as $petakItem) {
            $petakId = $petakItem->petak_id;

            // --- Query 1: biaya selesai sebelum simulasi ---
            $biayaLaluRows = DB::table('transaksi_biaya_simulasi_petak as tbp')
                ->join('setup_biaya as sb','tbp.biaya_id','=','sb.id_biaya')
                ->join('transaksi_biaya_simulasi as tb','tbp.trans_biaya_simulasi_id','=','tb.id')
                ->join('transaksi_biaya_simulasi_siklus as tbs','tbp.trans_biaya_simulasi_siklus_id','=','tbs.id')
                ->select('tbp.*','tb.no_transaksi','sb.nama_biaya')
                ->where('tbp.petak_id', $petakId)
                ->where('tb.id_simulasi', $getSimulasi->id_simulasi)
                ->where('tbs.siklus_id', $siklusId)
                ->when($tglMulaiSiklus, function($q) use ($tglMulaiSiklus){
                    $q->where('tbp.tanggal_mulai','>=',$tglMulaiSiklus);
                })
                ->where('tbp.tanggal_mulai','<=',$tglSimulasi)
                ->where('tbp.tanggal_selesai','<=',$tglSimulasi)
                ->get();

            $totalBiayaLalu = $biayaLaluRows->sum('nominal_petak');

            // --- Query 2: biaya berjalan (prorata) ---
            $biayaBerjalanRows = DB::table('transaksi_biaya_simulasi_petak as tbp')
                ->join('setup_biaya as sb','tbp.biaya_id','=','sb.id_biaya')
                ->join('transaksi_biaya_simulasi as tb','tbp.trans_biaya_simulasi_id','=','tb.id')
                ->join('transaksi_biaya_simulasi_siklus as tbs','tbp.trans_biaya_simulasi_siklus_id','=','tbs.id')
                ->select('tbp.*','tb.no_transaksi','sb.nama_biaya')
                ->where('tbp.petak_id', $petakId)
                ->where('tb.id_simulasi', $getSimulasi->id_simulasi)
                ->where('tbs.siklus_id', $siklusId)
                ->when($tglMulaiSiklus, function($q) use ($tglMulaiSiklus){
                    $q->where('tbp.tanggal_mulai','>=',$tglMulaiSiklus);
                })
                ->where('tbp.tanggal_mulai','<',$tglSimulasi)
                ->where('tbp.tanggal_selesai','>',$tglSimulasi)
                ->get();

            $totalProrata = 0;
            $detailAll = [];

            // biaya lalu
            foreach ($biayaLaluRows as $row) {
                $detailAll[] = [
                    'id_transaksi_biaya_simulasi_petak' => $row->id,
                    'no_transaksi' => $row->no_transaksi,
                    'nama_biaya' => $row->nama_biaya,
                    'tanggal_mulai' => $row->tanggal_mulai,
                    'tanggal_selesai' => $row->tanggal_selesai,
                    'nominal_petak' => (float)$row->nominal_petak,
                    'tipe_perhitungan' => 'full',
                    'biaya_hitung' => round($row->nominal_petak, 0)
                ];
            }

            // biaya prorata
            foreach ($biayaBerjalanRows as $row) {
                $tglMulai = Carbon::parse($row->tanggal_mulai);
                $tglSelesai = Carbon::parse($row->tanggal_selesai);

                $totalHari = $tglMulai->diffInDays($tglSelesai) + 1;
                $hariSampaiSimulasi = $tglMulai->diffInDays($tglSimulasi) + 1;
                $biayaPerHari = $row->nominal_petak / $totalHari;
                $biayaReal = $biayaPerHari * $hariSampaiSimulasi;

                $totalProrata += $biayaReal;

                $detailAll[] = [
                    'id_transaksi_biaya_simulasi_petak' => $row->id,
                    'no_transaksi' => $row->no_transaksi,
                    'nama_biaya' => $row->nama_biaya,
                    'tanggal_mulai' => $row->tanggal_mulai,
                    'tanggal_selesai' => $row->tanggal_selesai,
                    'nominal_petak' => (float)$row->nominal_petak,
                    'tipe_perhitungan' => 'prorata',
                    'total_hari' => $totalHari,
                    'biaya_per_hari' => round($biayaPerHari,2),
                    'hari_sampai_simulasi' => $hariSampaiSimulasi,
                    'biaya_hitung' => round($biayaReal, 0)
                ];
            }

            $totalBiayaSimulasi = $totalBiayaLalu + $totalProrata;

            $result[] = [
                'petak_id' => $petakId,
                'biaya_simulasi' => round($totalBiayaSimulasi,0),
                'detail_biaya' => $detailAll
            ];
        }

        return $result;
    }

    public function getBiayaSimulasi($simulasiId)
    {
        // get simulasi
        $getSimulasi = DB::table('transaksi_simulasi')->where('id_simulasi', $simulasiId)->firstOrFail();
        $tglSimulasi = Carbon::parse($getSimulasi->tanggal_simulasi);
        $siklusId = $getSimulasi->siklus_id;

        // Ambil tanggal mulai siklus
        $siklus = DB::table('setup_siklus')->where('id_siklus', $siklusId)->first();
        $tglMulaiSiklus = $siklus ? Carbon::parse($siklus->tanggal_mulai) : null;

        $petakList = SetupSiklusPetak::with('petak.blok')
            ->where('siklus_id', $siklusId)
            ->orderBy('id_siklus_petak','asc')
            ->get();

        foreach ($petakList as $petakItem) {
            $petakId = $petakItem->petak_id;

            // ambil id benur dari penamburan benur per petak
            // $penaburanBenurDetail = DB::table('penaburan_benur_detail')
            //     ->join('penaburan_benur','penaburan_benur.id_penaburan_benur','penaburan_benur_detail.id_penaburan_benur')
            //     ->where('penaburan_benur_detail.siklus_id', $siklusId)
            //     ->where('penaburan_benur_detail.id_petak', $petakId)
            //     ->orderBy('id_penaburan_benur_detail','desc')->first();
            // $petakItem->benur_id =$penaburanBenurDetail ? $penaburanBenurDetail->id_benur :null;
            // $petakItem->jenis_benur =$penaburanBenurDetail ? $penaburanBenurDetail->jenis_benur :null;
            // $petakItem->jumlah_benur =$penaburanBenurDetail ? $penaburanBenurDetail->jumlah_neto :null;
            // $petakItem->doc =$penaburanBenurDetail ?  Carbon::parse($penaburanBenurDetail->tanggal_penaburan)->diffInDays($tglSimulasi) + 1 :null;

            // Query 1: biaya selesai sebelum simulasi
            $biayaLaluRows = DB::table('transaksi_biaya_simulasi_petak as tbp')
                ->join('setup_biaya as sb','tbp.biaya_id','=','sb.id_biaya')
                ->join('transaksi_biaya_simulasi as tb','tbp.trans_biaya_simulasi_id','=','tb.id')
                ->join('transaksi_biaya_simulasi_siklus as tbs','tbp.trans_biaya_simulasi_siklus_id','=','tbs.id')
                ->select('tbp.*','tb.no_transaksi','sb.nama_biaya')
                ->where('tbp.petak_id', $petakId)
                ->where('tb.id_simulasi', $getSimulasi->id_simulasi)
                ->where('tbs.siklus_id', $siklusId)
                // mulai biaya >= mulai siklus
                ->when($tglMulaiSiklus, function($q) use ($tglMulaiSiklus){
                    $q->where('tbp.tanggal_mulai','>=',$tglMulaiSiklus);
                })
                ->where('tbp.tanggal_mulai','<=',$tglSimulasi)
                ->where('tbp.tanggal_selesai','<=',$tglSimulasi)
                ->get();

            $totalBiayaLalu = $biayaLaluRows->sum('nominal_petak');

            // Query 2: biaya berjalan saat simulasi
            $biayaBerjalanRows = DB::table('transaksi_biaya_simulasi_petak as tbp')
                ->join('setup_biaya as sb','tbp.biaya_id','=','sb.id_biaya')
                ->join('transaksi_biaya_simulasi as tb','tbp.trans_biaya_simulasi_id','=','tb.id')
                ->join('transaksi_biaya_simulasi_siklus as tbs','tbp.trans_biaya_simulasi_siklus_id','=','tbs.id')
                ->select('tbp.*','tb.no_transaksi','sb.nama_biaya')
                ->where('tbp.petak_id', $petakId)
                ->where('tb.id_simulasi', $getSimulasi->id_simulasi)
                ->where('tbs.siklus_id', $siklusId)
                ->when($tglMulaiSiklus, function($q) use ($tglMulaiSiklus){
                    $q->where('tbp.tanggal_mulai','>=',$tglMulaiSiklus);
                })
                ->where('tbp.tanggal_mulai','<',$tglSimulasi)
                ->where('tbp.tanggal_selesai','>',$tglSimulasi)
                ->get();

            $totalProrata = 0;
            $detailAll = [];

            // biaya lalu
            foreach ($biayaLaluRows as $row) {
                $detailAll[] = [
                    'id_transaksi_biaya_simulasi_petak' => $row->id,
                    'no_transaksi' => $row->no_transaksi,
                    'nama_biaya' => $row->nama_biaya,
                    'tanggal_mulai' => $row->tanggal_mulai,
                    'tanggal_selesai' => $row->tanggal_selesai,
                    'nominal_petak' => (float)$row->nominal_petak,
                    'tipe_perhitungan' => 'full',
                    'biaya_hitung' => round($row->nominal_petak, 0)
                ];
            }

            // biaya prorata
            foreach ($biayaBerjalanRows as $row) {
                $tglMulai = Carbon::parse($row->tanggal_mulai);
                $tglSelesai = Carbon::parse($row->tanggal_selesai);

                $totalHari = $tglMulai->diffInDays($tglSelesai) + 1;
                $hariSampaiSimulasi = $tglMulai->diffInDays($tglSimulasi) + 1;
                $biayaPerHari = $row->nominal_petak / $totalHari;
                $biayaReal = $biayaPerHari * $hariSampaiSimulasi;

                $totalProrata += $biayaReal;

                $detailAll[] = [
                    'id_transaksi_biaya_simulasi_petak' => $row->id,
                    'no_transaksi' => $row->no_transaksi,
                    'nama_biaya' => $row->nama_biaya,
                    'tanggal_mulai' => $row->tanggal_mulai,
                    'tanggal_selesai' => $row->tanggal_selesai,
                    'nominal_petak' => (float)$row->nominal_petak,
                    'tipe_perhitungan' => 'prorata',
                    'total_hari' => $totalHari,
                    'biaya_per_hari' => round($biayaPerHari,2),
                    'hari_sampai_simulasi' => $hariSampaiSimulasi,
                    'biaya_hitung' => round($biayaReal, 0)
                ];
            }

            $totalBiayaSimulasi = $totalBiayaLalu + $totalProrata;

            $petakItem->biaya_simulasi = round($totalBiayaSimulasi,0);
            $petakItem->detail_biaya = $detailAll;
        }

        return response()->json($petakList);
    }
}
