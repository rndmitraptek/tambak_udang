<?php

namespace App\Http\Controllers\ManajemenTambak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManajemenTambak\TransaksiBiayaSimulasi;
use App\Models\ManajemenTambak\TransaksiBiayaSimulasiSiklus;
use App\Models\ManajemenTambak\TransaksiBiayaSimulasiPetak;
use App\Models\ManajemenTambak\TransaksiSimulasi;
use App\Models\SetupBiaya;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiBiayaSimulasiController extends Controller
{
    //
    public function index()
    {
        return view('feature.manajemen-tambak.transaksi-biaya.index');
    }

    public function validasi()
    {
        return view('feature.manajemen-tambak.transaksi-biaya-validasi.index');
    }

    public function biayaList()
    {
        $biaya = SetupBiaya::with(['petak', 'lokasi.siklus'])->orderBy('nama_biaya','asc')->get();
        return response()->json($biaya);
    }

    public function petakList(Request $request)
    {
        // siklus_id bisa array (dari query string ?siklus_id[]=1&siklus_id[]=2)
        $siklusIds = $request->input('siklus_id', [0]);
        $petakId = $request->input('petak_id', 0);

        $query = \DB::table('setup_siklus_petak')
            ->join('setup_siklus', 'setup_siklus.id_siklus', '=', 'setup_siklus_petak.siklus_id')
            ->join('setup_petak', 'setup_petak.id_petak', '=', 'setup_siklus_petak.petak_id')
            ->join('setup_lokasi', 'setup_lokasi.id_lokasi', '=', 'setup_siklus.lokasi_id')
            ->whereNull('setup_petak.deleted_at')
            ->select([
                'setup_lokasi.nama_lokasi as nama_lokasi',
                'setup_siklus.id_siklus as siklus_id',
                'setup_petak.id_petak as petak_id',
                'setup_petak.nama_petak as nama_petak',
                'setup_siklus_petak.status_panen',
                'setup_petak.luas_petak as luas',
                \DB::raw('0 as persentase'),
                \DB::raw('0 as biaya_perpetak'),
            ]);

        if (!empty($siklusIds)) {
            $query->whereIn('setup_siklus.id_siklus', $siklusIds);
        }
        if (!empty($petakId) && $petakId != 0) {
            $query->whereIn('setup_petak.id_petak', [$petakId]);
        }

        return datatables()->of($query)->toJson();
    }

    public function data(Request $request)
    {
        $query = TransaksiBiayaSimulasi::with(['biaya','coa','siklus.siklus','simulasi'])
            ->select('transaksi_biaya_simulasi.*')
            ->orderBy('id', 'desc');

        return DataTables::of($query)
            ->addColumn('siklus', function($row){
                if ($row->siklus) {
                    return $row->siklus->map(function($s){
                        return $s->siklus->nama_siklus ?? '-';
                    })->implode('<br>');
                }
                return '-';
            })
            ->addColumn('biaya', fn($row) => $row->biaya->nama_biaya ?? '-')
            ->addColumn('coa', fn($row) => $row->coa->nama_coa ?? '-')
            ->addColumn('actions', function ($row) use ($request) {
                if($request->has('type') && $request->get('type') == 'validasi') {
                    if ($row->validated_by) {
                        return '<span class="m--font-success">Sudah divalidasi</span>';
                    } else {
                        return '
                        <a href="javascript:void(0)" onclick="validateTransaksi(\''.$row->uuid.'\')" 
                            class="m-portlet__nav-link btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill" 
                            title="Validasi">
                            <i class="m--font-success la la-check"></i>
                        </a>
                        ';
                    }
                } else {

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
                }
            })
            ->rawColumns(['siklus','actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'no_transaksi'  => 'required|unique:transaksi_biaya_simulasi,no_transaksi',
                'tanggal_transaksi' => 'required|date',
                'uuid_simulasi' => 'required',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai'=> 'nullable|date',
                'biaya_id'      => 'required|exists:setup_biaya,id_biaya',
                'nominal'       => 'required|numeric',
                'coa_id'        => 'nullable|exists:setup_coa,id_coa',
                'keterangan'    => 'nullable|string',
                'siklus'        => 'array', // array id siklus
                'petak'         => 'array', // array per siklus
            ]);

            //cek uuid_simulasi
            $getSimulasi =TransaksiSimulasi::where('uuid',$validated['uuid_simulasi'])->firstOrFail();

            // 1. simpan transaksi_biaya_simulasi
            $transBiaya = TransaksiBiayaSimulasi::create([
                'uuid'              => \Str::uuid(),
                'id_simulasi'      => $getSimulasi->id_simulasi,
                'no_transaksi'      => $validated['no_transaksi'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'tanggal_mulai'     => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai'   => $validated['tanggal_selesai'] ?? null,
                'biaya_id'          => $validated['biaya_id'],
                'nominal'           => $validated['nominal'],
                'coa_id'            => $validated['coa_id'] ?? null,
                'keterangan'        => $validated['keterangan'] ?? null,
            ]);

            // 2. simpan transaksi_biaya_simulasi_siklus
            $siklusMap = [];
            if (!empty($validated['siklus'])) {
                foreach ($validated['siklus'] as $siklusId) {
                    $tbSiklus = TransaksiBiayaSimulasiSiklus::create([
                        'trans_biaya_simulasi_id' => $transBiaya->id,
                        'siklus_id'      => $siklusId,
                    ]);
                    $siklusMap[$siklusId] = $tbSiklus->id; // simpan map untuk petak
                }
            }

            // 3. simpan transaksi_biaya_simulasi_petak
            if (!empty($validated['petak'])) {
                foreach ($validated['petak'] as $siklusId => $petaks) {
                    if (!isset($siklusMap[$siklusId])) continue;

                    $transSiklusId = $siklusMap[$siklusId];

                    foreach ($petaks as $p) {
                        TransaksiBiayaSimulasiPetak::create([
                            'trans_biaya_simulasi_id'        => $transBiaya->id,
                            'trans_biaya_simulasi_siklus_id' => $transSiklusId,
                            'petak_id'              => $p['petak_id'],
                            'biaya_id'              => $validated['biaya_id'],
                            'luas'                  => $p['luas'] ?? null,
                            'persentase'            => $p['persentase'] ?? 0,
                            'nominal_petak'         => $p['biaya_perpetak'] ?? 0,
                            'tanggal_mulai'         => $validated['tanggal_mulai'] ?? $validated['tanggal_transaksi'],
                            'tanggal_selesai'      => $validated['tanggal_selesai'] ?? $validated['tanggal_transaksi'],
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'data' => $transBiaya]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($uuid)
    {
        $data = TransaksiBiayaSimulasi::with(['biaya','coa','siklus.siklus','siklus.petak.petak'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        return response()->json($data);
    }

    public function update(Request $request, $uuid)
    {
        $trans = TransaksiBiayaSimulasi::where('uuid', $uuid)->firstOrFail();

        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'no_transaksi'  => 'required',
                'tanggal_transaksi' => 'required|date',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_selesai'=> 'nullable|date',
                'biaya_id'      => 'required|exists:setup_biaya,id_biaya',
                'nominal'       => 'required|numeric',
                'coa_id'        => 'nullable|exists:setup_coa,id_coa',
                'keterangan'    => 'nullable|string',
                'siklus'        => 'array', // array id siklus
                'petak'         => 'array', // array per siklus
            ]);

            $trans->update([
                'no_transaksi'      => $validated['no_transaksi'],
                'tanggal_transaksi' => $validated['tanggal_transaksi'],
                'tanggal_mulai'     => $validated['tanggal_mulai'] ?? null,
                'tanggal_selesai'   => $validated['tanggal_selesai'] ?? null,
                'biaya_id'          => $validated['biaya_id'],
                'nominal'           => $validated['nominal'],
                'coa_id'            => $validated['coa_id'] ?? null,
                'keterangan'        => $validated['keterangan'] ?? null,
                'validated_by'      => 1,
                'validated_at'      => now(),
            ]);

            // 2. simpan transaksi_biaya_simulasi_siklus
            TransaksiBiayaSimulasiSiklus::where('trans_biaya_simulasi_id', $trans->id)->forceDelete();
            $siklusMap = [];
            if (!empty($validated['siklus'])) {
                foreach ($validated['siklus'] as $siklusId) {
                    $tbSiklus = TransaksiBiayaSimulasiSiklus::create([
                        'trans_biaya_simulasi_id' => $trans->id,
                        'siklus_id'      => $siklusId,
                    ]);
                    $siklusMap[$siklusId] = $tbSiklus->id; // simpan map untuk petak
                }
            }

            // 3. simpan transaksi_biaya_simulasi_petak
            TransaksiBiayaSimulasiPetak::where('trans_biaya_simulasi_id', $trans->id)->forceDelete();
            if (!empty($validated['petak'])) {
                foreach ($validated['petak'] as $siklusId => $petaks) {
                    if (!isset($siklusMap[$siklusId])) continue;

                    $transSiklusId = $siklusMap[$siklusId];

                    foreach ($petaks as $p) {
                        TransaksiBiayaSimulasiPetak::create([
                            'trans_biaya_simulasi_id'        => $trans->id,
                            'trans_biaya_simulasi_siklus_id' => $transSiklusId,
                            'petak_id'              => $p['petak_id'],
                            'biaya_id'              => $validated['biaya_id'],
                            'luas'                  => $p['luas'] ?? null,
                            'persentase'            => $p['persentase'] ?? 0,
                            'nominal_petak'         => $p['biaya_perpetak'] ?? 0,
                            'tanggal_mulai'         => $validated['tanggal_mulai'] ?? $validated['tanggal_transaksi'],
                            'tanggal_selesai'       => $validated['tanggal_selesai'] ?? $validated['tanggal_transaksi'],
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'data' => $trans]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function action_validasi($uuid)
    {
        $trans = TransaksiBiayaSimulasi::where('uuid', $uuid)->firstOrFail();
        $trans->update([
            'validated_by'        => (Auth::user())?Auth::user()->id_user:1,
            'validated_at'        => now(),
        ]);
        return response()->json(['success' => true]);
    }

    public function destroy($uuid)
    {
        $trans = TransaksiBiayaSimulasi::where('uuid', $uuid)->firstOrFail();
        $trans->delete();
        return response()->json(['success' => true]);
    }

    private function generateNoTransaksi()
    {
        $last = TransaksiBiayaSimulasi::latest('id')->first();
        $num = $last ? $last->id+1 : 1;
        return "TR".date('Ymd').str_pad($num, 5, '0', STR_PAD_LEFT);
    }
}
