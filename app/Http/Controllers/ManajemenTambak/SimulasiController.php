<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManajemenTambak\TransaksiSimulasi;
use App\Models\ManajemenTambak\TransaksiSimulasiBiaya;
use App\Models\ManajemenTambak\PanenModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SetupLokasi;
use App\Models\SetupSiklus;
use App\Models\SetupSiklusPetak;

class SimulasiController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.simulasi.index');
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

        $petakList = SetupSiklusPetak::with('petak.blok')
            ->where('siklus_id', $siklusId)
            ->orderBy('id_siklus_petak','asc')
            ->get();

        foreach ($petakList as $petakItem) {
            $petakId = $petakItem->petak_id;

            // ambil id benur dari penamburan benur per petak
            $penaburanBenurDetail = DB::table('penaburan_benur_detail')
                ->join('penaburan_benur','penaburan_benur.id_penaburan_benur','penaburan_benur_detail.id_penaburan_benur')
                ->where('penaburan_benur_detail.siklus_id', $siklusId)
                ->where('penaburan_benur_detail.id_petak', $petakId)
                ->orderBy('id_penaburan_benur_detail','desc')->first();
            $petakItem->benur_id =$penaburanBenurDetail ? $penaburanBenurDetail->id_benur :null;
            $petakItem->jenis_benur =$penaburanBenurDetail ? $penaburanBenurDetail->jenis_benur :null;
            $petakItem->jumlah_benur =$penaburanBenurDetail ? $penaburanBenurDetail->jumlah_neto :null;
            $petakItem->doc =$penaburanBenurDetail ?  Carbon::parse($penaburanBenurDetail->tanggal_penaburan)->diffInDays($tglSimulasi) + 1 :null;

            // Query 1: biaya selesai sebelum simulasi
            $biayaLaluRows = DB::table('transaksi_biaya_petak as tbp')
                ->join('setup_biaya as sb','tbp.biaya_id','=','sb.id_biaya')
                ->join('transaksi_biaya as tb','tbp.trans_biaya_id','=','tb.id')
                ->select('tbp.*','tb.no_transaksi','sb.nama_biaya')
                ->where('tbp.petak_id', $petakId)
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
                ->join('transaksi_biaya as tb','tbp.trans_biaya_id','=','tb.id')
                ->select('tbp.*','tb.no_transaksi','sb.nama_biaya')
                ->where('tbp.petak_id', $petakId)
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

        // karena biaya & pendapatan sudah total per petak, cukup pakai nilai langsung
        // misal di tabel transaksi_simulasi_biaya sudah ada kolom total per petak
        // misal di tabel transaksi_simulasi_pendapatan juga ada kolom total per petak
        $petakIds = collect($simulasi->biaya)->pluck('petak_id')
            ->merge(collect($simulasi->pendapatan)->pluck('petak_id'))
            ->unique();

        foreach ($petakIds as $petakId) {
            // total biaya & pendapatan sudah langsung
            $totalBiaya = $simulasi->biaya
                ->where('petak_id', $petakId)
                ->sum('nominal_biaya');

            $totalPendapatan = $simulasi->pendapatan
                ->where('petak_id', $petakId)
                ->sum('pendapatan_subtotal');

            $labaRugi = $totalPendapatan - $totalBiaya;

            $detailBiaya = $simulasi->biaya
                ->where('petak_id', $petakId)
                ->first();
            $detailPendapatan = $simulasi->pendapatan
                ->where('petak_id', $petakId)
                ->first();

            //get panen
            $panen =PanenModel::where('id_petak',$petakId)->where('id_siklus',$simulasi->siklus_id)->sum('total');
            $totalPanen = $panen ? $panen : 0;
            if($detailPendapatan){
                $detailPendapatan->pendapatan_actual_partial =round($totalPanen,0);
            }

            $result[] = [
                'petak_id' => $petakId,
                'total_biaya' => round($totalBiaya,0),
                'total_pendapatan' => round($totalPendapatan,0),
                'pendapatan_actual_partial' => round($totalPanen,0),
                'laba_rugi' => round($labaRugi,0),
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

        return response()->json($simulasi);
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
}
