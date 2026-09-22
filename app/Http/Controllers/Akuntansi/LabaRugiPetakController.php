<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\Controller;
use App\Models\SetupLokasi;
use App\Models\SetupSiklus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Laporan laba rugi per petak berdasarkan siklus.
 * Biaya   : transaksi_biaya_petak (transaksi biaya, penaburan benur, penggunaan pakan), COA dari setup_biaya.
 * Pendapatan : panen + panen_detail (actual, bukan simulasi), COA dari setup_item.
 */
class LabaRugiPetakController extends Controller
{
    public function index()
    {
        return view('feature.akuntansi.laba_rugi_petak.index');
    }

    public function lokasi()
    {
        $data = SetupLokasi::select('id_lokasi', 'nama_lokasi')->orderBy('nama_lokasi', 'asc')->get();
        return response()->json(['success' => true, 'data' => $data, 'message' => '']);
    }

    public function siklus($lokasiId)
    {
        $data = SetupSiklus::where('lokasi_id', $lokasiId)
            ->select('id_siklus', 'nama_siklus', 'tanggal_mulai', 'tanggal_selesai', 'status')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();
        return response()->json(['success' => true, 'data' => $data, 'message' => '']);
    }

    public function summary(Request $req)
    {
        try {
            $req->validate(['siklus_id' => 'required|integer']);
            $siklus = SetupSiklus::with('lokasi')->findOrFail($req->siklus_id);

            // biaya per petak per coa
            $biayaRows = $this->biayaQuery($siklus->id_siklus)
                ->select(
                    'tbp.petak_id',
                    DB::raw('COALESCE(sc.id_coa,0) as id_coa'),
                    DB::raw("COALESCE(sc.kode_coa,'-') as kode_coa"),
                    DB::raw("COALESCE(sc.nama_coa,'(Tanpa COA)') as nama_coa"),
                    DB::raw('SUM(tbp.nominal_petak) as total')
                )
                ->groupBy('tbp.petak_id', 'sc.id_coa', 'sc.kode_coa', 'sc.nama_coa')
                ->get();

            // pendapatan panen per petak per coa
            $pendapatanRows = $this->pendapatanQuery($siklus->id_siklus)
                ->select(
                    'p.id_petak as petak_id',
                    DB::raw('COALESCE(sc.id_coa,0) as id_coa'),
                    DB::raw("COALESCE(sc.kode_coa,'-') as kode_coa"),
                    DB::raw("COALESCE(sc.nama_coa,'(Tanpa COA)') as nama_coa"),
                    DB::raw('SUM(CAST(pd.jumlah AS double precision)) as jumlah'),
                    DB::raw('SUM(CAST(pd.subtotal AS double precision)) as total')
                )
                ->groupBy('p.id_petak', 'sc.id_coa', 'sc.kode_coa', 'sc.nama_coa')
                ->get();

            // biomassa panen per petak
            $biomassa = DB::table('panen')
                ->whereNull('deleted_at')
                ->where('id_siklus', $siklus->id_siklus)
                ->select('id_petak', DB::raw('SUM(CAST(jumlah AS double precision)) as biomassa'))
                ->groupBy('id_petak')
                ->pluck('biomassa', 'id_petak');

            // daftar petak: petak dalam siklus + petak yang punya transaksi
            $petakIds = DB::table('setup_siklus_petak')
                ->join('setup_petak', 'setup_petak.id_petak', '=', 'setup_siklus_petak.petak_id')
                ->where('setup_siklus_petak.siklus_id', $siklus->id_siklus)
                ->whereNull('setup_petak.deleted_at')
                ->pluck('setup_siklus_petak.petak_id')
                ->merge($biayaRows->pluck('petak_id'))
                ->merge($pendapatanRows->pluck('petak_id'))
                ->unique()
                ->values();

            $petakList = DB::table('setup_petak as sp')
                ->leftJoin('setup_blok as sb', 'sb.id_blok', '=', 'sp.blok_id')
                ->leftJoin('setup_siklus_petak as ssp', function ($j) use ($siklus) {
                    $j->on('ssp.petak_id', '=', 'sp.id_petak')->where('ssp.siklus_id', $siklus->id_siklus);
                })
                ->whereIn('sp.id_petak', $petakIds)
                ->select('sp.id_petak', 'sp.nama_petak', 'sp.luas_petak', 'sb.nama_blok', 'ssp.status_panen')
                ->get()
                ->sort(function ($a, $b) {
                    return strnatcasecmp($a->nama_blok ?? '', $b->nama_blok ?? '')
                        ?: strnatcasecmp($a->nama_petak ?? '', $b->nama_petak ?? '');
                })
                ->values();

            $biayaByPetak = $biayaRows->groupBy('petak_id');
            $pendapatanByPetak = $pendapatanRows->groupBy('petak_id');

            $data = [];
            foreach ($petakList as $p) {
                $biayaCoa = $this->mapCoa($biayaByPetak->get($p->id_petak, collect()));
                $pendapatanCoa = $this->mapCoa($pendapatanByPetak->get($p->id_petak, collect()));
                $totalBiaya = collect($biayaCoa)->sum('total');
                $totalPendapatan = collect($pendapatanCoa)->sum('total');
                $bio = (float) ($biomassa[$p->id_petak] ?? 0);

                $data[] = [
                    'petak_id'         => $p->id_petak,
                    'nama_blok'        => $p->nama_blok,
                    'nama_petak'       => $p->nama_petak,
                    'luas_petak'       => (float) $p->luas_petak,
                    'status_panen'     => $p->status_panen ?? '-',
                    'biomassa'         => $bio,
                    'total_pendapatan' => round($totalPendapatan, 0),
                    'total_biaya'      => round($totalBiaya, 0),
                    'laba_rugi'        => round($totalPendapatan - $totalBiaya, 0),
                    'hpp_per_kg'       => $bio > 0 ? round($totalBiaya / $bio, 0) : 0,
                    'margin'           => $totalPendapatan != 0 ? round(($totalPendapatan - $totalBiaya) / $totalPendapatan * 100, 2) : 0,
                    'biaya_coa'        => $biayaCoa,
                    'pendapatan_coa'   => $pendapatanCoa,
                ];
            }

            $grand = collect($data);
            $totalBiaya = $grand->sum('total_biaya');
            $totalPendapatan = $grand->sum('total_pendapatan');
            $totalBiomassa = $grand->sum('biomassa');
            $total = [
                'luas_petak'       => $grand->sum('luas_petak'),
                'biomassa'         => $totalBiomassa,
                'total_pendapatan' => $totalPendapatan,
                'total_biaya'      => $totalBiaya,
                'laba_rugi'        => $totalPendapatan - $totalBiaya,
                'hpp_per_kg'       => $totalBiomassa > 0 ? round($totalBiaya / $totalBiomassa, 0) : 0,
                'margin'           => $totalPendapatan != 0 ? round(($totalPendapatan - $totalBiaya) / $totalPendapatan * 100, 2) : 0,
                'biaya_coa'        => $this->mapCoa($biayaRows),
                'pendapatan_coa'   => $this->mapCoa($pendapatanRows),
            ];

            return response()->json(['success' => true, 'data' => [
                'siklus' => [
                    'id_siklus'       => $siklus->id_siklus,
                    'nama_siklus'     => $siklus->nama_siklus,
                    'nama_lokasi'     => optional($siklus->lokasi)->nama_lokasi,
                    'tanggal_mulai'   => $siklus->tanggal_mulai,
                    'tanggal_selesai' => $siklus->tanggal_selesai,
                    'status'          => $siklus->status,
                ],
                'petak' => $data,
                'total' => $total,
            ], 'message' => '']);
        } catch (\Exception $err) {
            return response()->json(['success' => false, 'message' => $err->getMessage()]);
        }
    }

    /**
     * Rincian transaksi per COA. petak_id kosong = semua petak dalam siklus.
     */
    public function detail(Request $req)
    {
        try {
            $req->validate([
                'siklus_id' => 'required|integer',
                'tipe'      => 'required|in:biaya,pendapatan',
                'id_coa'    => 'required|integer',
                'petak_id'  => 'nullable|integer',
            ]);

            if ($req->tipe == 'biaya') {
                $query = $this->biayaQuery($req->siklus_id)
                    ->leftJoin('setup_petak as sp', 'sp.id_petak', '=', 'tbp.petak_id')
                    ->leftJoin('setup_blok as bl', 'bl.id_blok', '=', 'sp.blok_id')
                    ->select(
                        'tb.tanggal_transaksi as tanggal',
                        'tb.no_transaksi',
                        'sb.nama_biaya',
                        'tb.keterangan',
                        'tbp.tanggal_mulai',
                        'tbp.tanggal_selesai',
                        'tbp.persentase',
                        'tbp.nominal_petak as nominal',
                        'bl.nama_blok',
                        'sp.nama_petak'
                    )
                    ->orderBy('tb.tanggal_transaksi', 'asc')
                    ->orderBy('tb.no_transaksi', 'asc');
                $petakCol = 'tbp.petak_id';
            } else {
                $query = $this->pendapatanQuery($req->siklus_id)
                    ->leftJoin('setup_customer as cu', 'cu.id_customer', '=', 'pd.id_customer')
                    ->leftJoin('setup_petak as sp', 'sp.id_petak', '=', 'p.id_petak')
                    ->leftJoin('setup_blok as bl', 'bl.id_blok', '=', 'sp.blok_id')
                    ->select(
                        'p.tanggal_panen as tanggal',
                        'p.no_panen',
                        'p.jenis_panen',
                        'cu.nama_customer',
                        'si.nama_item',
                        DB::raw('CAST(pd.jumlah AS double precision) as jumlah'),
                        DB::raw('CAST(pd.harga AS double precision) as harga'),
                        DB::raw('CAST(pd.subtotal AS double precision) as nominal'),
                        'bl.nama_blok',
                        'sp.nama_petak'
                    )
                    ->orderBy('p.tanggal_panen', 'asc')
                    ->orderBy('p.no_panen', 'asc');
                $petakCol = 'p.id_petak';
            }

            if ($req->id_coa == 0) {
                $query->whereNull('sc.id_coa');
            } else {
                $query->where('sc.id_coa', $req->id_coa);
            }
            if (!empty($req->petak_id)) {
                $query->where($petakCol, $req->petak_id);
            }

            return response()->json(['success' => true, 'data' => $query->get(), 'message' => '']);
        } catch (\Exception $err) {
            return response()->json(['success' => false, 'message' => $err->getMessage()]);
        }
    }

    private function biayaQuery($siklusId)
    {
        return DB::table('transaksi_biaya_petak as tbp')
            ->join('transaksi_biaya as tb', 'tb.id', '=', 'tbp.trans_biaya_id')
            ->join('transaksi_biaya_siklus as tbs', 'tbs.id', '=', 'tbp.trans_biaya_siklus_id')
            ->leftJoin('setup_biaya as sb', 'sb.id_biaya', '=', 'tbp.biaya_id')
            ->leftJoin('setup_coa as sc', 'sc.id_coa', '=', 'sb.coa_id')
            ->whereNull('tb.deleted_at')
            ->where('tbs.siklus_id', $siklusId);
    }

    private function pendapatanQuery($siklusId)
    {
        return DB::table('panen as p')
            ->join('panen_detail as pd', 'pd.id_panen', '=', 'p.id_panen')
            ->leftJoin('setup_item as si', 'si.id_item', '=', 'pd.id_item')
            ->leftJoin('setup_coa as sc', 'sc.id_coa', '=', 'si.id_coa')
            ->whereNull('p.deleted_at')
            ->whereNull('pd.deleted_at')
            ->where('p.id_siklus', $siklusId);
    }

    // gabungkan baris (per petak / semua petak) menjadi total per coa, urut kode coa
    private function mapCoa($rows)
    {
        return collect($rows)
            ->groupBy('id_coa')
            ->map(function ($g) {
                $first = $g->first();
                return [
                    'id_coa'   => (int) $first->id_coa,
                    'kode_coa' => $first->kode_coa,
                    'nama_coa' => $first->nama_coa,
                    'jumlah'   => (float) $g->sum(fn($r) => $r->jumlah ?? 0),
                    'total'    => round((float) $g->sum('total'), 0),
                ];
            })
            ->sortBy('kode_coa', SORT_NATURAL)
            ->values()
            ->all();
    }
}
